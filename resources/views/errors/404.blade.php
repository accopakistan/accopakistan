<x-site-layout :title="__('Page Not Available')">
    <div class="position-relative overflow-hidden min-vh-75 d-flex align-items-center justify-content-center" 
         style="background: radial-gradient(circle at 50% 50%, rgba(12, 42, 77, 0.04) 0%, transparent 70%); display: flex; align-items: center; justify-content: center; padding: 6rem 0;">
        
        <!-- Background Grid -->
        <div class="position-absolute w-100 h-100 opacity-25" 
             style="background-image: radial-gradient(#0c2a4d 1px, transparent 1px); background-size: 24px 24px; pointer-events: none; z-index: -1; top: 0; left: 0;">
        </div>

        <div class="container text-center" style="max-width: 680px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <div class="row justify-content-center w-100">
                <div class="col-12" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <!-- Premium Icon Badge -->
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center" 
                         style="background: rgba(12, 42, 77, 0.08); color: var(--c-navy, #0c2a4d); border-radius: 50%; width: 6.5rem; height: 6.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; border: 1px solid rgba(12, 42, 77, 0.15); animation: pulse 2.5s infinite ease-in-out;">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display: block;">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>

                    <!-- Main Text -->
                    <h1 class="display-2" style="font-family: 'Fraunces', Georgia, serif; font-weight: 500; color: var(--c-ink, #0c2a4d); margin-bottom: 1.25rem;">
                        {{ __('Page Not Available') }}
                    </h1>
                    
                    <p style="font-family: 'Inter', system-ui, -apple-system, sans-serif; font-size: 1.1rem; color: #5a6e85; line-height: 1.65; margin-bottom: 3.5rem; max-width: 580px; margin-left: auto; margin-right: auto;">
                        {{ __('We apologize for the inconvenience, but the link you followed is currently unavailable or has been permanently removed. It may be offline for updates or scheduling restrictions.') }}
                    </p>

                    <!-- CTA Actions -->
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1.25rem; margin: 0 auto;">
                        <a href="{{ url('/') }}" class="btn btn--gold" style="text-decoration: none;">
                            {{ __('Return Home') }}
                            <x-icon name="arrow-right" />
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn--outline" style="text-decoration: none; border-color: var(--c-ink, #0c2a4d); color: var(--c-ink, #0c2a4d);">
                            {{ __('Contact Support') }}
                            <x-icon name="mail" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inject CSS keyframes for the pulse ring -->
    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(12, 42, 77, 0.3);
            }
            70% {
                transform: scale(1.03);
                box-shadow: 0 0 0 12px rgba(12, 42, 77, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(12, 42, 77, 0);
            }
        }
        .min-vh-75 {
            min-height: 70vh;
        }
    </style>
</x-site-layout>
