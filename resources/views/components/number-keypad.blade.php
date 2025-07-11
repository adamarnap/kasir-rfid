@push('css')
    <style>
        .numeric-keypad {
            max-width: 300px;
            margin: 0 auto;
        }

        .pin-number,
        #clear-pin,
        #clear-all-pin {
            height: 60px;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .pin-number:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pin-number:active {
            transform: translateY(0);
        }

        #clear-pin:hover,
        #clear-all-pin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
<div class="mt-4">
    <div class="numeric-keypad">
        <div class="row g-2">
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number" data-number="1">1</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number" data-number="2">2</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="3">3</button>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="4">4</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="5">5</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="6">6</button>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="7">7</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="8">8</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="9">9</button>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-4">
                <button type="button" class="btn btn-outline-secondary btn-lg w-100" id="clear-pin">
                    <i class="bi bi-backspace"></i>
                </button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                    data-number="0">0</button>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-outline-danger btn-lg w-100" id="clear-all-pin">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the PIN input field
            const formInputAttachedKeypad = document.querySelector('.attach-keypad');

            // Get all number buttons
            const numberButtons = document.querySelectorAll('.pin-number');
            const clearButton = document.getElementById('clear-pin');
            const clearAllButton = document.getElementById('clear-all-pin');

            // Add click event to number buttons
            numberButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const number = this.getAttribute('data-number');

                    // Add number to input 
                    
                    formInputAttachedKeypad.value += number;

                    // Add visual feedback
                    this.classList.add('btn-primary');
                    this.classList.remove('btn-outline-primary');

                    setTimeout(() => {
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-outline-primary');
                    }, 150);

                    // Focus back to input
                    formInputAttachedKeypad.focus();
                });
            });

            // Clear last digit (backspace)
            clearButton.addEventListener('click', function() {
                if (formInputAttachedKeypad && formInputAttachedKeypad.value.length > 0) {
                    formInputAttachedKeypad.value = formInputAttachedKeypad.value.slice(0, -1);
                }
                formInputAttachedKeypad.focus();
            });

            // Clear all digits
            clearAllButton.addEventListener('click', function() {
                if (formInputAttachedKeypad) {
                    formInputAttachedKeypad.value = '';
                }
                formInputAttachedKeypad.focus();
            });

            // Focus on PIN input when modal is opened
            const pinModal = document.getElementById('pinModal');
            pinModal.addEventListener('shown.bs.modal', function() {
                if (formInputAttachedKeypad) {
                    formInputAttachedKeypad.focus();
                }
            });
        });
    </script>
@endpush
