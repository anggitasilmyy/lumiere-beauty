<?php

namespace App\Http\Controllers;

// Mengimport model Role dan User yang digunakan untuk proses register dan pengecekan role.
use App\Models\Role;
use App\Models\User;

// Mengimport Request untuk mengambil data dari form.
use Illuminate\Http\Request;

// Auth digunakan untuk proses login dan logout.
use Illuminate\Support\Facades\Auth;

// Hash digunakan untuk mengamankan password sebelum disimpan ke database.
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses data login dari form login.
    public function login(Request $request)
    {
        // Validasi input login.
        // Email wajib diisi dengan format email, dan password wajib diisi.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mengambil nilai checkbox "remember me".
        // Jika dicentang, session login akan disimpan lebih lama.
        $remember = $request->boolean('remember');

        // Mengecek email dan password user ke database menggunakan fitur Auth Laravel.
        // Jika email atau password salah, user dikembalikan ke halaman login.
        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // Membuat ulang session setelah login berhasil agar lebih aman.
        $request->session()->regenerate();

        // Mengecek apakah akun user masih aktif.
        // Jika akun tidak aktif, user langsung logout dan kembali ke halaman login.
        if (!auth()->user()->is_active) {
            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Akun Anda sedang tidak aktif.');
        }

        // Mengecek role user.
        // Jika user adalah admin, maka diarahkan ke dashboard admin.
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Berhasil login sebagai admin.');
        }

        // Jika user bukan admin, maka dianggap sebagai customer dan diarahkan ke homepage.
        return redirect()->route('home')
            ->with('success', 'Berhasil login.');
    }

    // Menampilkan halaman register untuk membuat akun customer baru.
    public function showRegister()
    {
        return view('auth.register');
    }

    // Memproses data register dari form register.
    public function register(Request $request)
    {
        // Validasi data register.
        // Email harus unik, password minimal 6 karakter,
        // dan konfirmasi password harus sama dengan password.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        // Menyimpan data user baru ke tabel users.
        // Password di-hash agar tidak tersimpan dalam bentuk asli.
        // User baru otomatis menjadi customer, aktif, membership Bronze, dan poin awal 0.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'membership_level_id' => 1,
            'total_points' => 0,
            'is_active' => true,
        ]);

        // Mengambil role customer dari tabel roles.
        $customerRole = Role::where('name', 'customer')->first();

        // Menghubungkan user baru dengan role customer melalui tabel role_user.
        // syncWithoutDetaching digunakan agar role lama tidak terhapus jika sudah ada.
        if ($customerRole) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        }

        // Setelah register berhasil, user diarahkan ke halaman login.
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // Mengeluarkan user dari sistem dan menghapus session login.
    public function logout(Request $request)
    {
        // Logout user dari sistem Auth Laravel.
        Auth::logout();

        // Menghapus session lama agar tidak bisa digunakan kembali.
        $request->session()->invalidate();

        // Membuat ulang token CSRF untuk keamanan setelah logout.
        $request->session()->regenerateToken();

        // Setelah logout, user diarahkan kembali ke homepage.
        return redirect()->route('home')
            ->with('success', 'Anda berhasil logout.');
    }
}