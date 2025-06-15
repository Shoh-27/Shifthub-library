<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Tarjima qilish') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .drag-drop-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .drag-drop-area.dragover {
            border-color: #007bff;
            background: #e7f3ff;
        }
        .progress-container {
            display: none;
            margin-top: 20px;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
        }
        .payment-info {
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">{{ __('Hujjatni tarjima qilish') }}</h1>

    <!-- Bildirishnomalar -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjima formasi -->
    <form action="{{ route('translate') }}" method="POST" enctype="multipart/form-data" id="translate-form">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Fayl yuklash -->
                <div class="mb-3">
                    <label for="file" class="form-label">{{ __('Fayl yuklash (PDF, DOC, DOCX)') }}</label>
                    <div class="drag-drop-area" id="drag-drop-area">
                        <p class="mb-0">{{ __('Faylni bu yerga sudrab keling yoki tanlash uchun bosing') }}</p>
                        <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" class="d-none" required>
                        <small class="text-muted">{{ __('Qo‘llab-quvvatlanadigan formatlar: PDF, DOC, DOCX') }}</small>
                    </div>
                    @error('file')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Til tanlash -->
                <div class="mb-3">
                    <label for="target_lang" class="form-label">{{ __('Tarjima tili') }}</label>
                    <select name="target_lang" id="target_lang" class="form-select" required>
                        <option value="en">{{ __('Inglizcha') }}</option>
                        <option value="uz">{{ __('O‘zbekcha') }}</option>
                    </select>
                    @error('target_lang')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Professional tarjima -->
                <div class="mb-3 form-check">
                    <input type="checkbox" name="professional" id="professional" value="1" class="form-check-input">
                    <label for="professional" class="form-check-label">{{ __('Professional sifatli tarjima (OpenAI)') }}</label>
                    <small class="text-muted d-block">{{ __('Yuqori sifatli va kontekstga mos tarjima') }}</small>
                </div>

                <!-- To‘lov oynasi -->
                <div class="payment-info mb-3" id="payment-info">
                    <label for="card" class="form-label">{{ __('To‘lov kartasi raqami') }}</label>
                    <input type="text" name="card_number" id="card" class="form-control" placeholder="**** **** **** 1234">
                    <small class="text-muted">{{ __('To‘lovni amalga oshirish uchun karta maʼlumotlarini kiriting') }}</small>
                </div>

                <!-- Progress bar -->
                <div class="progress-container" id="progress-container">
                    <div class="progress" role="progressbar">
                        <div class="progress-bar progress-bar-animated progress-bar-striped" style="width: 0%"></div>
                    </div>
                    <p class="text-center mt-2">{{ __('Tarjima qilinmoqda...') }}</p>
                </div>

                <!-- Submit tugmasi -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">{{ __('Tarjima qilish') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bootstrap JS va Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dragDropArea = document.getElementById('drag-drop-area');
        const fileInput = document.getElementById('file');
        const form = document.getElementById('translate-form');
        const submitBtn = document.getElementById('submit-btn');
        const progressContainer = document.getElementById('progress-container');
        const progressBar = progressContainer.querySelector('.progress-bar');
        const professionalCheckbox = document.getElementById('professional');
        const paymentInfo = document.getElementById('payment-info');

        // Drag-and-drop
        dragDropArea.addEventListener('click', () => fileInput.click());
        dragDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragDropArea.classList.add('dragover');
        });
        dragDropArea.addEventListener('dragleave', () => {
            dragDropArea.classList.remove('dragover');
        });
        dragDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dragDropArea.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            dragDropArea.querySelector('p').textContent = fileInput.files[0]?.name || '{{ __("Faylni bu yerga sudrab keling yoki tanlash uchun bosing") }}';
        });
        fileInput.addEventListener('change', () => {
            dragDropArea.querySelector('p').textContent = fileInput.files[0]?.name || '{{ __("Faylni bu yerga sudrab keling yoki tanlash uchun bosing") }}';
        });

        // To‘lov oynasi
        professionalCheckbox.addEventListener('change', () => {
            if (professionalCheckbox.checked) {
                paymentInfo.style.display = 'block';
            } else {
                paymentInfo.style.display = 'none';
            }
        });

        // Form submit
        form.addEventListener('submit', (e) => {
            submitBtn.disabled = true;
            progressContainer.style.display = 'block';
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = `${progress}%`;
                if (progress >= 90) clearInterval(interval);
            }, 400);
        });
    });
</script>
</body>
</html>
