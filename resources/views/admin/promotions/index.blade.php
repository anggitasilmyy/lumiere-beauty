@extends('layouts.admin')

@section('title', 'Manage Promotions - Lumiere Beauty Clinic')

@section('admin_content')
    <section class="section-block">
        <div class="container admin-page-shell">
            <div class="admin-toolbar reveal d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
                <div>
                    <span class="eyebrow eyebrow-dark">Promotion Management</span>
                    <h1>Manage Promotions</h1>
                    <p class="mb-0">Add, edit, activate, and delete public or membership-only promotions.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-outline-secondary">Dashboard</a>
                </div>
            </div>

            <div class="table-card reveal mb-4">
                <div class="table-header">
                    <div>
                        <h2>Add Promotion</h2>
                        <p>Create a new promotion for customers or selected membership levels.</p>
                    </div>
                </div>

                <form action="{{ route('admin.promotions.store') }}" method="POST" class="admin-form-grid">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Promotion Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="promo_code" class="form-label">Promotion Code</label>
                        <input type="text" id="promo_code" name="promo_code" value="{{ old('promo_code') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="discount_percent" class="form-label">Discount Percent</label>
                        <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" min="0" max="100" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="minimum_membership_level_id" class="form-label">Minimum Membership</label>
                        <select id="minimum_membership_level_id" name="minimum_membership_level_id" class="form-select">
                            <option value="">None</option>
                            @foreach($membershipLevels as $level)
                                <option value="{{ $level->id }}" {{ old('minimum_membership_level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->level_name }} or higher
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                    </div>

                    <div class="form-group admin-form-full">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="admin-checkbox-group admin-form-full d-flex flex-column flex-md-row gap-3">
                        <label class="form-check">
                            <input type="checkbox" name="membership_only" value="1" class="form-check-input" {{ old('membership_only') ? 'checked' : '' }}>
                            <span class="form-check-label">Membership only</span>
                        </label>

                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <span class="form-check-label">Active</span>
                        </label>
                    </div>

                    <div class="form-group admin-form-full">
                        <label for="treatment_ids" class="form-label">Applied Treatments</label>
                        <select id="treatment_ids" name="treatment_ids[]" multiple class="form-select">
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->id }}" {{ collect(old('treatment_ids'))->contains($treatment->id) ? 'selected' : '' }}>
                                    {{ $treatment->treatment_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Hold Ctrl and click to select multiple treatments. Leave empty if this promotion applies to all treatments.
                        </small>
                    </div>

                    <div class="admin-form-actions admin-form-full">
                        <button type="submit" class="btn btn-primary">Save Promotion</button>
                    </div>
                </form>
            </div>

            <div class="table-card reveal mb-4">
                <form action="{{ route('admin.promotions.index') }}" method="GET" class="admin-filter-grid">
                    <div class="form-group">
                        <label for="search" class="form-label">Search Promotions</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Title, promotion code, or description"
                        >
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="admin-filter-actions d-flex flex-column flex-md-row gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>

            <div class="promotion-admin-list">
                @forelse($promotions as $promotion)
                    <article class="promotion-admin-card reveal">
                        <div class="promotion-admin-header">
                            <div>
                                <span class="promo-tag">
                                    {{ $promotion->membership_only ? 'Member Promotion' : 'Public Promotion' }}
                                </span>

                                <h2>{{ $promotion->title }}</h2>
                                <p>{{ $promotion->description ?? 'No description available.' }}</p>
                            </div>

                            <div class="booking-admin-badges">
                                @if($promotion->is_active)
                                    <span class="status-pill status-paid">Active</span>
                                @else
                                    <span class="status-pill status-cancelled">Inactive</span>
                                @endif

                                <span class="status-pill status-waiting_verification">
                                    {{ number_format($promotion->discount_percent, 0) }}% OFF
                                </span>
                            </div>
                        </div>

                        <div class="booking-admin-meta">
                            <div>
                                <h4>Promotion Code</h4>
                                <p>{{ $promotion->promo_code ?? '-' }}</p>
                            </div>

                            <div>
                                <h4>Membership</h4>
                                <p>
                                    @if($promotion->membership_only)
                                        {{ $promotion->minimumMembershipLevel->level_name ?? 'Member' }}
                                    @else
                                        All customers
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h4>Period</h4>
                                <p>
                                    {{ $promotion->start_date?->format('d M Y') ?? '-' }}
                                    to
                                    {{ $promotion->end_date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <h4>Applied Treatments</h4>
                                <p>
                                    @forelse($promotion->treatments as $treatment)
                                        {{ $treatment->treatment_name }}{{ !$loop->last ? ', ' : '' }}
                                    @empty
                                        All treatments
                                    @endforelse
                                </p>
                            </div>
                        </div>

                        <details class="promotion-edit-box mt-3">
                            <summary>Edit Promotion</summary>

                            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="admin-form-grid mt-3">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" value="{{ $promotion->title }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Promotion Code</label>
                                    <input type="text" name="promo_code" value="{{ $promotion->promo_code }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Discount Percent</label>
                                    <input type="number" name="discount_percent" value="{{ $promotion->discount_percent }}" min="0" max="100" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Minimum Membership</label>
                                    <select name="minimum_membership_level_id" class="form-select">
                                        <option value="">None</option>
                                        @foreach($membershipLevels as $level)
                                            <option value="{{ $level->id }}" {{ $promotion->minimum_membership_level_id == $level->id ? 'selected' : '' }}>
                                                {{ $level->level_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group admin-form-full">
                                    <label class="form-label">Applied Treatments</label>
                                    <select name="treatment_ids[]" multiple class="form-select">
                                        @foreach($treatments as $treatment)
                                            <option value="{{ $treatment->id }}" {{ $promotion->treatments->contains($treatment->id) ? 'selected' : '' }}>
                                                {{ $treatment->treatment_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Hold Ctrl and click to select multiple treatments. Leave empty if this promotion applies to all treatments.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" value="{{ $promotion->start_date?->format('Y-m-d') }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" value="{{ $promotion->end_date?->format('Y-m-d') }}" class="form-control">
                                </div>

                                <div class="form-group admin-form-full">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="4" class="form-control">{{ $promotion->description }}</textarea>
                                </div>

                                <div class="admin-checkbox-group admin-form-full d-flex flex-column flex-md-row gap-3">
                                    <label class="form-check">
                                        <input type="checkbox" name="membership_only" value="1" class="form-check-input" {{ $promotion->membership_only ? 'checked' : '' }}>
                                        <span class="form-check-label">Membership only</span>
                                    </label>

                                    <label class="form-check">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $promotion->is_active ? 'checked' : '' }}>
                                        <span class="form-check-label">Active</span>
                                    </label>
                                </div>

                                <div class="admin-form-actions admin-form-full">
                                    <button type="submit" class="btn btn-primary">Update Promotion</button>
                                </div>
                            </form>
                        </details>

                        <form
                            action="{{ route('admin.promotions.destroy', $promotion) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this promotion?')"
                            class="promotion-delete-form mt-3"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger-soft">Delete Promotion</button>
                        </form>
                    </article>
                @empty
                    <div class="table-card reveal">
                        <p>No promotions available.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrap mt-4">
                {{ $promotions->links() }}
            </div>
        </div>
    </section>
@endsection