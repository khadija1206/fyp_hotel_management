<section>
    <header class="mb-3">
        <h2 class="h5">{{ __('Delete Account') }}</h2>
        <p class="text-muted small mb-0">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion-modal">
        {{ __('Delete Account') }}
    </button>

    <div class="modal fade" id="confirm-user-deletion-modal" tabindex="-1" aria-labelledby="confirm-user-deletion-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h2 class="modal-title h5" id="confirm-user-deletion-label">{{ __('Are you sure you want to delete your account?') }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mb-0">
                            <x-input-label for="password" value="{{ __('Password') }}" />
                            <x-text-input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="{{ __('Password') }}"
                            />
                            <x-input-error :messages="$errors->userDeletion->get('password')" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('confirm-user-deletion-modal');
                if (el && window.bootstrap && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        </script>
    @endif
</section>
