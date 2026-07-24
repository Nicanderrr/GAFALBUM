@php
    $editing = isset($image);
    $selectedStatus = old('status', $image->status ?? 'published');
    $selectedCoverId = old('cover_media_id', $image->cover_media_id ?? optional($image->coverMedia)->id);
@endphp

<style>
    .admin-editor-shell {
        display: grid;
        gap: 1.5rem;
    }

    .admin-editor-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.35rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-editor-header h3 {
        margin: 0;
        padding: 0;
        border: 0;
    }

    .admin-editor-header p {
        margin: 0.35rem 0 0;
        color: #64748b;
        font-size: 0.84rem;
    }

    .admin-editor-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: minmax(0, 1.5fr) minmax(320px, 0.9fr);
        padding: 1.5rem;
    }

    .admin-editor-panel {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .admin-editor-panel h4 {
        margin: 0;
        padding: 1rem 1.1rem;
        font-size: 0.95rem;
        color: #111827;
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-editor-fields {
        display: grid;
        gap: 1rem;
        padding: 1.1rem;
    }

    .admin-editor-field {
        display: grid;
        gap: 0.45rem;
    }

    .admin-editor-field label {
        font-size: 0.82rem;
        font-weight: 800;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .admin-editor-field input,
    .admin-editor-field select,
    .admin-editor-field textarea {
        width: 100%;
        border: 1px solid #dbe1ea;
        border-radius: 9px;
        background: #fff;
        color: #111827;
        font-size: 0.92rem;
        padding: 0.9rem 1rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .admin-editor-field textarea {
        min-height: 140px;
        resize: vertical;
    }

    .admin-editor-field input:focus,
    .admin-editor-field select:focus,
    .admin-editor-field textarea:focus {
        border-color: #b91c1c;
        box-shadow: 0 0 0 4px rgba(185, 28, 28, 0.08);
    }

    .admin-editor-help {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.55;
    }

    .admin-status-grid {
        display: grid;
        gap: 0.75rem;
    }

    .admin-status-option {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 0.85rem;
        align-items: start;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.95rem 1rem;
        background: #fff;
    }

    .admin-status-option input {
        margin-top: 0.15rem;
    }

    .admin-status-option strong {
        display: block;
        font-size: 0.9rem;
        color: #111827;
    }

    .admin-status-option span {
        display: block;
        margin-top: 0.2rem;
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .admin-media-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        padding: 1.1rem;
    }

    .admin-media-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .admin-media-preview {
        position: relative;
        height: 180px;
        background: #111827;
    }

    .admin-media-preview img,
    .admin-media-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .admin-media-type {
        position: absolute;
        top: 0.8rem;
        right: 0.8rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.84);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.35rem 0.6rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .admin-media-body {
        display: grid;
        gap: 0.7rem;
        padding: 0.95rem;
    }

    .admin-media-body strong {
        font-size: 0.88rem;
        color: #111827;
    }

    .admin-media-actions {
        display: grid;
        gap: 0.55rem;
    }

    .admin-media-choice {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 0.8rem;
        color: #334155;
    }

    .admin-media-choice.is-disabled {
        color: #94a3b8;
    }

    .admin-error-box {
        margin: 0 1.5rem;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #991b1b;
        padding: 1rem 1.1rem;
    }

    .admin-error-box ul {
        margin: 0;
        padding-left: 1.2rem;
        display: grid;
        gap: 0.35rem;
    }

    .admin-editor-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0 1.5rem 1.5rem;
    }

    .admin-editor-meta {
        color: #64748b;
        font-size: 0.8rem;
    }

    .admin-editor-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-editor-btn {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 800;
        text-decoration: none;
        padding: 0.85rem 1.15rem;
        cursor: pointer;
        border: 1px solid #dbe1ea;
        background: #fff;
        color: #334155;
    }

    .admin-editor-btn.primary {
        border-color: #7f1d1d;
        background: #800000;
        color: #fff;
    }

    @media (max-width: 1100px) {
        .admin-editor-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .admin-editor-header,
        .admin-editor-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .admin-editor-actions {
            width: 100%;
            justify-content: stretch;
        }

        .admin-editor-actions > * {
            flex: 1 1 0;
        }
    }

    @media (max-width: 760px) {
        .admin-editor-grid {
            padding: 1rem;
        }

        .admin-editor-fields,
        .admin-media-grid {
            padding: 1rem;
        }

        .admin-editor-inline-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<div class="data-table-container admin-editor-shell">
    <div class="admin-editor-header">
        <div>
            <h3>{{ $editing ? 'Edit Event' : 'Create Event' }}</h3>
            <p>Manage the event title, status, pricing, and the media collection users will browse.</p>
        </div>
        <a href="{{ route('admin.images.index') }}" class="admin-editor-btn">Back to Events</a>
    </div>

    @if($errors->any())
        <div class="admin-error-box">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $editing ? route('admin.images.update', $image) : route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($editing)
            @method('PUT')
        @endif

        <div class="admin-editor-grid">
            <div style="display: grid; gap: 1.5rem;">
                <section class="admin-editor-panel">
                    <h4>Event Details</h4>
                    <div class="admin-editor-fields">
                        <div class="admin-editor-field">
                            <label for="title">Event / Program Name</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $image->title ?? '') }}" required>
                        </div>

                        <div class="admin-editor-field">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Short context for the event, unit, or program.">{{ old('description', $image->description ?? '') }}</textarea>
                        </div>

                        <div class="admin-editor-inline-grid" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem;">
                            <div class="admin-editor-field">
                                <label for="category_id">Category</label>
                                <select id="category_id" name="category_id">
                                    <option value="">No category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $image->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="admin-editor-field">
                                <label for="price">Price (GHS)</label>
                                <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $image->price ?? '') }}" required>
                            </div>

                            <div class="admin-editor-field">
                                <label for="media">Upload Files</label>
                                <input id="media" type="file" name="media[]" {{ $editing ? '' : 'required' }} multiple accept="image/*,video/*">
                            </div>
                        </div>

                        <div class="admin-editor-help">
                            The first file in a new event must be an image so the gallery and admin tables have a thumbnail. You can add extra photos or videos later without creating a separate event.
                        </div>
                    </div>
                </section>

                @if($editing)
                    <section class="admin-editor-panel">
                        <h4>Event Media</h4>
                        <div class="admin-media-grid">
                            @foreach($image->media as $media)
                                <label class="admin-media-card">
                                    <div class="admin-media-preview">
                                        @if($media->media_type === 'video')
                                            <video src="{{ Storage::url($media->file_path) }}" muted playsinline></video>
                                        @else
                                            <img src="{{ Storage::url($media->file_path) }}" alt="{{ $image->title }}">
                                        @endif
                                        <span class="admin-media-type">{{ $media->media_type }}</span>
                                    </div>
                                    <div class="admin-media-body">
                                        <strong>File {{ $loop->iteration }}</strong>
                                        <div class="admin-media-actions">
                                            @if($media->media_type === 'image')
                                                <span class="admin-media-choice">
                                                    <input type="radio" name="cover_media_id" value="{{ $media->id }}" @checked((int) $selectedCoverId === $media->id)>
                                                    Use as thumbnail
                                                </span>
                                            @else
                                                <span class="admin-media-choice is-disabled">Videos cannot be used as thumbnails.</span>
                                            @endif
                                            <span class="admin-media-choice">
                                                <input type="checkbox" name="remove_media_ids[]" value="{{ $media->id }}">
                                                Remove from event
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="admin-editor-panel">
                <h4>Publishing</h4>
                <div class="admin-editor-fields">
                    <div class="admin-status-grid">
                        <label class="admin-status-option">
                            <input type="radio" name="status" value="draft" @checked($selectedStatus === 'draft')>
                            <div>
                                <strong>Draft</strong>
                                <span>Keep the event off the live gallery while you finish uploads and pricing.</span>
                            </div>
                        </label>
                        <label class="admin-status-option">
                            <input type="radio" name="status" value="published" @checked($selectedStatus === 'published')>
                            <div>
                                <strong>Published</strong>
                                <span>Show the event to users immediately and allow purchases.</span>
                            </div>
                        </label>
                        <label class="admin-status-option">
                            <input type="radio" name="status" value="archived" @checked($selectedStatus === 'archived')>
                            <div>
                                <strong>Archived</strong>
                                <span>Hide the event from active browsing without deleting its media or payment history.</span>
                            </div>
                        </label>
                    </div>

                    <div class="admin-editor-help">
                        {{ $editing ? 'Current event ID: #'.$image->id.'. Update the thumbnail or remove individual files here without losing the entire event.' : 'After publishing, users will see this event in the gallery and can pay per file.' }}
                    </div>
                </div>
            </aside>
        </div>

        <div class="admin-editor-footer">
            <div class="admin-editor-meta">
                {{ $editing ? 'Created '.$image->created_at?->format('d M Y \a\t h:i A') : 'New events can contain multiple photos and videos under one program.' }}
            </div>
            <div class="admin-editor-actions">
                <a href="{{ route('admin.images.index') }}" class="admin-editor-btn">Cancel</a>
                <button type="submit" class="admin-editor-btn primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </form>
</div>
