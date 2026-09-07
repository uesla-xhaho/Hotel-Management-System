@extends('layouts.guest')

@section('content')
<style>
    .hm-no-rooms-card {
        max-width: 920px;
        margin: -24px auto 0 !important;
        transform: translateX(26px);
    }

    .hm-public-back {
        border: none;
        color: #fff;
        background: var(--hm-navy);
    }

    .hm-public-back:hover {
        color: #fff;
        background: var(--hm-navy);
        border: none;
    }

    .hm-inline-feedback .alert {
        display: inline-block;
        width: auto;
        max-width: min(620px, 100%);
    }

    @media (max-width: 992px) {
        .hm-no-rooms-card {
            margin-top: -8px !important;
            transform: none;
        }
    }
</style>
<div class="hm-fade-in mb-4 {{ !is_null($search) ? 'd-none' : '' }}" data-delay="1" id="hero-section">
    <div class="hm-hero-grid">
        <div class="hm-hero">
            <div class="hm-badge mb-3">Guest Booking</div>
            <div class="hm-hero-title mb-2">Plan your stay</div>
            <p class="hm-hero-subtitle">Select your dates and guests. You can secure a room without logging in.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="hm-feature">Fast check-in</span>
                <span class="hm-feature">Instant confirmation</span>
                <span class="hm-feature">No account needed</span>
            </div>
        </div>
        <div class="hm-card p-3 p-md-4">
            <div class="hm-section-title mb-3">Choose your stay</div>
            <form id="availability-form" method="GET" action="{{ route('public-availability') }}" class="row g-3">
                <div class="col-12">
                    <label for="hotel_id" class="form-label">Hotel</label>
                    <select id="hotel_id" name="hotel_id" class="form-select hm-input" required>
                        <option value="" disabled {{ !isset($search['hotel_id']) ? 'selected' : '' }}>Select hotel</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ (string) ($search['hotel_id'] ?? '') === (string) $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <label for="checkin" class="form-label">Check-in</label>
                    <input type="text" id="checkin" name="checkin" class="form-control hm-input hm-date" value="{{ $search['checkin'] ?? '' }}" data-datepicker="checkin" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <label for="checkout" class="form-label">Check-out</label>
                    <input type="text" id="checkout" name="checkout" class="form-control hm-input hm-date" value="{{ $search['checkout'] ?? '' }}" data-datepicker="checkout" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <label for="guests" class="form-label">Guests</label>
                    <input type="number" id="guests" name="guests" class="form-control hm-input" min="1" value="{{ $search['guests'] ?? 1 }}" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <button type="submit" class="hm-pill hm-pill-primary w-100">Search availability</button>
                </div>
                <div class="col-12 d-none" id="availability-error">
                    <div class="alert alert-danger mb-0"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="availability-results">
    @if (!is_null($search))
        <div class="hm-card hm-fade-in p-4 p-md-5 mb-4 {{ $rooms->isEmpty() ? 'hm-no-rooms-card' : '' }}" data-delay="2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <h3 class="hm-section-title mb-0">Available rooms</h3>
                <div class="hm-muted">
                    Hotel {{ $selectedHotel->name ?? ($search['hotel_name'] ?? 'N/A') }} -
                    Stay {{ $search['checkin'] }} to {{ $search['checkout'] }} -
                    {{ $search['guests'] }} guests
                </div>
            </div>

            @if ($rooms->isEmpty())
                <div class="text-muted">No rooms are available for these dates. Try different dates.</div>
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('public-booking') }}" class="hm-pill hm-pill-primary text-decoration-none d-inline-block">Go back</a>
                </div>
            @else
                <form id="booking-form" method="POST" action="{{ route('public-booking.store') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $search['hotel_id'] }}">
                    <input type="hidden" name="checkin" value="{{ $search['checkin'] }}">
                    <input type="hidden" name="checkout" value="{{ $search['checkout'] }}">
                    <input type="hidden" name="guests" value="{{ $search['guests'] }}">

                    @if ($errors->any())
                        <div class="col-12 hm-inline-feedback">
                            <div class="alert alert-danger mb-0">
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif
                    @if (session('message'))
                        <div class="col-12 hm-inline-feedback">
                            <div class="alert alert-success mb-0">{{ session('message') }}</div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="col-12 hm-inline-feedback">
                            <div class="alert alert-danger mb-0">{{ session('error') }}</div>
                        </div>
                    @endif

                    <div class="col-12">
                        <h4 class="hm-section-title mb-2">Pick your room</h4>
                    </div>
                    @foreach ($rooms as $room)
                        <div class="col-md-6">
                            <label class="hm-room-card w-100">
                                <div class="hm-room-media">
                                    @if (!empty($room->image))
                                        @php
                                            $roomImagePath = ltrim($room->image, '/');
                                            if (strpos($roomImagePath, 'rooms/') !== 0) {
                                                $roomImagePath = 'rooms/' . $roomImagePath;
                                            }
                                        @endphp
                                        <img src="{{ asset('storage/' . $roomImagePath) }}" alt="Room {{ $room->room_number ?? $room->id }}">
                                    @else
                                        <div class="hm-room-media-placeholder">No photo available</div>
                                    @endif
                                </div>
                                <div class="hm-room-body">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="fw-semibold">Room {{ $room->room_number ?? ('#' . $room->id) }}</div>
                                            <div class="hm-muted">{{ $room->category }}</div>
                                            <div class="hm-room-hotel">
                                                Hotel: {{ optional($room->hotel)->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="hm-room-price">{{ $room->price }} / night</div>
                                    </div>
                                    @if (!empty($room->description))
                                        <p class="hm-room-desc">{{ $room->description }}</p>
                                    @endif
                                    <div class="hm-divider"></div>
                                    <div class="hm-room-details">
                                        <div><span class="hm-room-label">Capacity</span><span>{{ $room->capacity }} guests</span></div>
                                        <div><span class="hm-room-label">Beds</span><span>{{ $room->nrofbeds }}</span></div>
                                        <div><span class="hm-room-label">Air condition</span><span>{{ ((int) $room->aircondition) === 1 ? 'Yes' : 'No' }}</span></div>
                                        <div><span class="hm-room-label">Balcony</span><span>{{ $room->balcony }}</span></div>
                                        <div><span class="hm-room-label">Status</span><span>{{ $room->status }}</span></div>
                                        <div><span class="hm-room-label">Hotel address</span><span>{{ optional($room->hotel)->address ?? 'N/A' }}</span></div>
                                        <div><span class="hm-room-label">Hotel phone</span><span>{{ optional($room->hotel)->phone ?? 'N/A' }}</span></div>
                                        <div><span class="hm-room-label">Hotel email</span><span>{{ optional($room->hotel)->email ?? 'N/A' }}</span></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <input class="hm-room-radio" type="radio" name="room_id" value="{{ $room->id }}" required>
                                    <span class="hm-room-select">
                                        <span class="hm-room-radio-dot"></span>
                                        <span>Select room</span>
                                    </span>
                                </div>
                            </label>
                        </div>
                    @endforeach

                    <div class="col-12">
                        <h4 class="hm-section-title mb-2 mt-2">Guest details</h4>
                        <div class="hm-muted">We will use this information to confirm your booking.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="personal_id" class="form-label">Personal ID</label>
                        <input type="text" id="personal_id" name="personal_id" class="form-control hm-input" value="{{ old('personal_id') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full name</label>
                        <input type="text" id="name" name="name" class="form-control hm-input" value="{{ old('name') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select hm-input" required>
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                    <div class="col-md-4">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="text" id="birthdate" name="birthdate" class="form-control hm-input hm-date" value="{{ old('birthdate') }}" data-datepicker="birthdate" placeholder="YYYY-MM-DD" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control hm-input" value="{{ old('phone') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label for="comment" class="form-label">Comments</label>
                        <input type="text" id="comment" name="comment" class="form-control hm-input" value="{{ old('comment') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12 hm-inline-feedback d-none" id="booking-feedback">
                        <div class="alert mb-0"></div>
                    </div>
                    <div class="col-12 d-flex justify-content-center gap-2">
                        <a href="{{ route('public-booking') }}" class="hm-pill hm-public-back text-decoration-none d-inline-flex align-items-center">Go back</a>
                        <button type="submit" class="hm-pill hm-pill-primary">Book now</button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>

<script>
    (function () {
        function initPublicBooking() {
        function initDatepickers(root) {
            if (typeof flatpickr === 'undefined') {
                return;
            }

            const scope = root || document;
            const inputs = scope.querySelectorAll('[data-datepicker]');

            inputs.forEach((input) => {
                if (input._flatpickr) {
                    input._flatpickr.destroy();
                }

                const type = input.dataset.datepicker;
                const options = {
                    dateFormat: 'Y-m-d',
                    allowInput: false,
                    clickOpens: true,
                    disableMobile: true
                };

                if (type === 'checkin') {
                    options.minDate = 'today';
                    options.onChange = function (selectedDates, dateStr) {
                        const checkout = scope.querySelector('[data-datepicker="checkout"]');
                        if (checkout && checkout._flatpickr) {
                            checkout._flatpickr.set('minDate', dateStr || 'today');
                        }
                    };
                }

                if (type === 'checkout') {
                    options.minDate = 'today';
                }

                if (type === 'birthdate') {
                    options.maxDate = 'today';
                    options.allowInput = true;
                }

                flatpickr(input, options);
                input.readOnly = type !== 'birthdate';
            });
        }

        const availabilityForm = document.getElementById('availability-form');
        const availabilityResults = document.getElementById('availability-results');
        const availabilityError = document.getElementById('availability-error');
        const heroSection = document.getElementById('hero-section');

        function clearFieldErrors(form) {
            if (!form) return;
            form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach((el) => (el.textContent = ''));
        }

        function setFieldErrors(form, errors) {
            if (!form || !errors) return;
            Object.keys(errors).forEach((key) => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    field.classList.add('is-invalid');
                    const feedback = field.parentElement.querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = errors[key][0];
                    }
                }
            });
        }

        const imageBase = "{{ asset('storage') }}";
        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderRooms(data) {
            if (!data || !data.search) {
                availabilityResults.innerHTML = '';
                return;
            }

            if (heroSection) {
                heroSection.classList.add('d-none');
            }

            const rooms = data.rooms || [];
            const search = data.search;
            let html = `
                <div class="hm-card hm-fade-in p-4 p-md-5 mb-4${rooms.length ? '' : ' hm-no-rooms-card'}" data-delay="2">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                        <h3 class="hm-section-title mb-0">Available rooms</h3>
                        <div class="hm-muted">Hotel ${escapeHtml(search.hotel_name ?? 'N/A')} - Stay ${search.checkin} to ${search.checkout} - ${search.guests} guests</div>
                    </div>
            `;

            if (!rooms.length) {
                html += `
                    <div class="text-muted">No rooms are available for these dates. Try different dates.</div>
                    <div class="mt-3 d-flex justify-content-end">
                        <a href="{{ route('public-booking') }}" class="hm-pill hm-pill-primary text-decoration-none d-inline-block">Go back</a>
                    </div>
                </div>
                `;
                availabilityResults.innerHTML = html;
                return;
            }

            html += `
                <form id="booking-form" method="POST" action="{{ route('public-booking.store') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="hotel_id" value="${search.hotel_id}">
                    <input type="hidden" name="checkin" value="${search.checkin}">
                    <input type="hidden" name="checkout" value="${search.checkout}">
                    <input type="hidden" name="guests" value="${search.guests}">
                    <div class="col-12">
                        <h4 class="hm-section-title mb-2">Pick your room</h4>
                    </div>
            `;

            rooms.forEach((room) => {
                let roomImage = '';
                if (room.image) {
                    const normalizedImage = String(room.image).replace(/^\/+/, '');
                    const roomImagePath = normalizedImage.startsWith('rooms/') ? normalizedImage : `rooms/${normalizedImage}`;
                    roomImage = `${imageBase}/${roomImagePath}`;
                }
                const airConditionLabel = (String(room.aircondition).toLowerCase() === 'yes' || String(room.aircondition) === '1') ? 'Yes' : 'No';
                const description = room.description ? escapeHtml(room.description) : '';
                const hotel = room.hotel || {};
                html += `
                    <div class="col-md-6">
                        <label class="hm-room-card w-100">
                            <div class="hm-room-media">
                                ${roomImage ? `<img src="${roomImage}" alt="Room ${escapeHtml(room.room_number || room.id)}">` : '<div class="hm-room-media-placeholder">No photo available</div>'}
                            </div>
                            <div class="hm-room-body">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-semibold">Room ${escapeHtml(room.room_number || ('#' + room.id))}</div>
                                        <div class="hm-muted">${escapeHtml(room.category ?? '')}</div>
                                        <div class="hm-room-hotel">Hotel: ${escapeHtml(hotel.name ?? 'N/A')}</div>
                                    </div>
                                    <div class="hm-room-price">${escapeHtml(room.price ?? '')} / night</div>
                                </div>
                                ${description ? `<p class="hm-room-desc">${description}</p>` : ''}
                                <div class="hm-divider"></div>
                                <div class="hm-room-details">
                                    <div><span class="hm-room-label">Capacity</span><span>${escapeHtml(room.capacity)} guests</span></div>
                                    <div><span class="hm-room-label">Beds</span><span>${escapeHtml(room.nrofbeds)}</span></div>
                                    <div><span class="hm-room-label">Air condition</span><span>${escapeHtml(airConditionLabel)}</span></div>
                                    <div><span class="hm-room-label">Balcony</span><span>${escapeHtml(room.balcony ?? '')}</span></div>
                                    <div><span class="hm-room-label">Status</span><span>${escapeHtml(room.status ?? '')}</span></div>
                                    <div><span class="hm-room-label">Hotel address</span><span>${escapeHtml(hotel.address ?? 'N/A')}</span></div>
                                    <div><span class="hm-room-label">Hotel phone</span><span>${escapeHtml(hotel.phone ?? 'N/A')}</span></div>
                                    <div><span class="hm-room-label">Hotel email</span><span>${escapeHtml(hotel.email ?? 'N/A')}</span></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <input class="hm-room-radio" type="radio" name="room_id" value="${room.id}" required>
                                <span class="hm-room-select">
                                    <span class="hm-room-radio-dot"></span>
                                    <span>Select room</span>
                                </span>
                            </div>
                        </label>
                    </div>
                `;
            });

            html += `
                    <div class="col-12">
                        <h4 class="hm-section-title mb-2 mt-2">Guest details</h4>
                        <div class="hm-muted">We will use this information to confirm your booking.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="personal_id" class="form-label">Personal ID</label>
                        <input type="text" id="personal_id" name="personal_id" class="form-control hm-input" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full name</label>
                        <input type="text" id="name" name="name" class="form-control hm-input" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select hm-input" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="text" id="birthdate" name="birthdate" class="form-control hm-input hm-date" data-datepicker="birthdate" placeholder="YYYY-MM-DD" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control hm-input" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label for="comment" class="form-label">Comments</label>
                        <input type="text" id="comment" name="comment" class="form-control hm-input">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12 hm-inline-feedback d-none" id="booking-feedback">
                        <div class="alert mb-0"></div>
                    </div>
                    <div class="col-12 d-flex justify-content-center gap-2">
                        <a href="{{ route('public-booking') }}" class="hm-pill hm-public-back text-decoration-none d-inline-flex align-items-center">Go back</a>
                        <button type="submit" class="hm-pill hm-pill-primary">Book now</button>
                    </div>
                </form>
                </div>
            `;

            availabilityResults.innerHTML = html;
        }

        function attachBookingHandler() {
            const bookingForm = document.getElementById('booking-form');
            if (!bookingForm) return;

            bookingForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearFieldErrors(bookingForm);

                const feedback = document.getElementById('booking-feedback');
                const alert = feedback ? feedback.querySelector('.alert') : null;

                function hideFeedback() {
                    if (!feedback || !alert) {
                        return;
                    }

                    feedback.classList.add('d-none');
                    alert.textContent = '';
                }

                function showFeedback(type, text) {
                    if (!feedback || !alert) {
                        return;
                    }

                    feedback.classList.remove('d-none');
                    alert.className = `alert alert-${type} mb-0`;
                    alert.textContent = text;

                    if (feedback._hideTimeout) {
                        window.clearTimeout(feedback._hideTimeout);
                    }

                    feedback._hideTimeout = window.setTimeout(hideFeedback, 4000);
                }

                if (feedback) {
                    if (feedback._hideTimeout) {
                        window.clearTimeout(feedback._hideTimeout);
                        feedback._hideTimeout = null;
                    }
                    hideFeedback();
                }

                try {
                    const response = await fetch(bookingForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: new FormData(bookingForm)
                    });

                    if (response.status === 422) {
                        const data = await response.json();
                        setFieldErrors(bookingForm, data.errors);
                        return;
                    }

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Booking failed.');
                    }

                    showFeedback('success', data.message || 'Booking confirmed.');

                    bookingForm.reset();
                } catch (error) {
                    showFeedback('danger', error.message || 'Booking failed.');
                }
            });
        }

        availabilityForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearFieldErrors(availabilityForm);
            availabilityError.classList.add('d-none');
            availabilityError.querySelector('.alert').textContent = '';

            try {
                const params = new URLSearchParams(new FormData(availabilityForm));
                const response = await fetch(`${availabilityForm.action}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.status === 422) {
                    const data = await response.json();
                    setFieldErrors(availabilityForm, data.errors);
                    return;
                }

                const data = await response.json();
                renderRooms(data);
                initDatepickers(availabilityResults);
                attachBookingHandler();
            } catch (error) {
                availabilityError.classList.remove('d-none');
                availabilityError.querySelector('.alert').textContent = 'Unable to fetch availability. Try again.';
            }
        });

        attachBookingHandler();
        initDatepickers(document);
        }

        if (typeof flatpickr === 'undefined') {
            window.addEventListener('load', initPublicBooking, { once: true });
        } else {
            initPublicBooking();
        }
    })();
</script>
@endsection
