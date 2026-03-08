{{-- resources/views/portal/complaint/track.blade.php --}}
@extends('layouts.portal')

@section('title', 'ቅሬታ ተከታተል')

@section('content')

<div class="breadcrumb-portal">
    <div class="container">
        <h2><i class="bi bi-search me-2"></i>ቅሬታ ተከታተል (Track Complaint)</h2>
        <nav aria-label="breadcrumb" data-aos="fade-down" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Track Status</li>
            </ol>
        </nav>
        <p class="text-white-50">የቲኬት ቁጥርዎን በማስገባት የቅሬታዎን ሁኔታ ይከታተሉ / Track your complaint status using your ticket
            number.</p>
    </div>
</div>

<section id="track" class="track section">
    <div class="container" data-aos="fade-up">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <form action="{{ route('complaint.check') }}" method="POST" id="trackForm">
                            @csrf

                            <div class="mb-3">
                                <label for="ticket_number" class="form-label">የቲኬት ቁጥር</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-ticket"></i></span>
                                    <input type="text" name="ticket_number" id="ticket_number"
                                        class="form-control form-control-lg @error('ticket_number') is-invalid @enderror"
                                        placeholder="ለምሳሌ፡ CMP-20240306-123456" value="{{ old('ticket_number') }}"
                                        required>
                                    @error('ticket_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">የቲኬት ቁጥርዎን በትክክል ያስገቡ</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-search me-2"></i>ፈልግ
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <p class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                ቲኬት ቁጥር ከጠፋዎት? እባክዎ በስልክ ቁጥር <strong>+251 11 123 4567</strong> ደውለው ያነጋግሩን።
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Status Check -->
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ፈጣን ሁኔታ ማወቂያ</h5>
                            <p class="card-text">በቅርብ ጊዜ የጠየቁትን ቲኬት ቁጥር እዚህ ያያሉ።</p>

                            <div id="recentSearches" class="mt-3">
                                <!-- Recent searches will be loaded via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load recent searches from localStorage
            loadRecentSearches();

            // Handle form submission with AJAX
            const form = document.getElementById('trackForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const ticketNumber = document.getElementById('ticket_number').value;

                // Store in recent searches
                saveToRecentSearches(ticketNumber);

                // Submit form normally
                this.submit();
            });
        });

        function saveToRecentSearches(ticket) {
            let searches = JSON.parse(localStorage.getItem('recentTicketSearches') || '[]');

            // Remove if already exists
            searches = searches.filter(t => t !== ticket);

            // Add to beginning
            searches.unshift(ticket);

            // Keep only last 5
            searches = searches.slice(0, 5);

            localStorage.setItem('recentTicketSearches', JSON.stringify(searches));
            loadRecentSearches();
        }

        function loadRecentSearches() {
            const searches = JSON.parse(localStorage.getItem('recentTicketSearches') || '[]');
            const container = document.getElementById('recentSearches');

            if (searches.length === 0) {
                container.innerHTML = '<p class="text-muted small">ምንም የቅርብ ጊዜ ፍለጋ የለም</p>';
                return;
            }

            let html = '<div class="list-group">';
            searches.forEach(ticket => {
                html += `
                                            <a href="{{ route('complaint.track') }}?ticket=${ticket}" 
                                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                ${ticket}
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        `;
            });
            html += '</div>';

            container.innerHTML = html;
        }
    </script>
@endpush