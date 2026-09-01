<x-custom-dashboard>
    <style>
        .hero-admin-wrap {
            display: grid;
            gap: 1.25rem;
        }

        .hero-admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .hero-admin-title {
            margin: 0;
            color: #111827;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .hero-admin-subtitle {
            margin: 0.35rem 0 0;
            color: #6b7280;
            font-size: 0.92rem;
        }

        .hero-group {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
        }

        .hero-group__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(180deg, #ffffff 0%, #fcfcfc 100%);
        }

        .hero-group__header h4 {
            margin: 0;
            color: #111827;
            font-size: 1rem;
            font-weight: 800;
        }

        .hero-group__header p {
            margin: 0.35rem 0 0;
            color: #6b7280;
            font-size: 0.86rem;
        }

        .hero-group__count {
            flex: 0 0 auto;
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
            background: #fdf2f2;
            color: #800000;
            font-size: 0.75rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .hero-group__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }

        .hero-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .hero-card__preview {
            height: 160px;
            background: #111827;
        }

        .hero-card__preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-card__empty {
            display: flex;
            height: 100%;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-weight: 800;
        }

        .hero-card__body {
            padding: 14px;
        }

        .hero-card__label {
            display: block;
            margin-bottom: 0.75rem;
            color: #111827;
            font-weight: 800;
        }

        .hero-card__key {
            margin: -0.35rem 0 0.8rem;
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .hero-card__input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
        }

        .hero-card__hint {
            margin: 0.65rem 0 0;
            color: #6b7280;
            font-size: 0.88rem;
        }

        .hero-admin-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        @media (max-width: 720px) {
            .hero-admin-header,
            .hero-group__header,
            .hero-admin-actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .hero-group__grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="data-table-container">
        <div class="hero-admin-wrap">
            <div class="hero-admin-actions">
                <div>
                    <h3 class="hero-admin-title">Hero Images</h3>
                    <p class="hero-admin-subtitle">Grouped by where each image appears so you can update related assets together.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back</a>
            </div>

            @if(session('success'))
                <div style="padding: 12px 14px; border-radius: 6px; color: #166534; background: #dcfce7; font-weight: 800;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="padding: 12px 14px; border-radius: 6px; color: #991b1b; background: #fee2e2; font-weight: 800;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.site-heroes.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="hero-admin-wrap">
                    @foreach($heroGroups as $groupName => $keys)
                        <section class="hero-group">
                            <div class="hero-group__header">
                                <div>
                                    <h4>{{ $groupName }}</h4>
                                    <p>
                                        @if($groupName === 'Dashboard')
                                            Backgrounds and how-it-works images used on the admin and user dashboards.
                                        @elseif($groupName === 'Homepage')
                                            The quick-action carousel images on the public homepage.
                                        @else
                                            General portal assets used across the system.
                                        @endif
                                    </p>
                                </div>
                                <div class="hero-group__count">{{ count($keys) }} items</div>
                            </div>

                            <div class="hero-group__grid">
                                @foreach($keys as $key)
                                    <div class="hero-card">
                                        <div class="hero-card__preview">
                                            @if(isset($heroes[$key]))
                                                <img src="{{ asset(Storage::url($heroes[$key]->image_path)) }}" alt="{{ $heroLabels[$key] }} hero">
                                            @else
                                                <div class="hero-card__empty">No image uploaded</div>
                                            @endif
                                        </div>
                                        <div class="hero-card__body">
                                            <label class="hero-card__label" for="{{ $key }}">{{ $heroLabels[$key] }}</label>
                                            <div class="hero-card__key">{{ $key }}</div>
                                            <input id="{{ $key }}" class="hero-card__input" type="file" name="{{ $key }}" accept="image/*">
                                            <p class="hero-card__hint">JPG, PNG, or WEBP. Use a wide image for best results.</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <button type="submit" style="margin-top: 22px; padding: 10px 20px; background: var(--primary-color, #4f46e5); color: white; border: none; border-radius: 4px; cursor: pointer;">Save Hero Images</button>
            </form>
        </div>
    </div>
</x-custom-dashboard>
