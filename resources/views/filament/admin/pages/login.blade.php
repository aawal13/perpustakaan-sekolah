<x-filament-panels::page.simple>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .custom-login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5af19 0%, #f12711 50%, #ff6b6b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: -1.5rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            padding: 32px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 60%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .login-header svg {
            width: 64px;
            height: 64px;
            color: white;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .login-header h1 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-top: 16px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .login-footer {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            margin-top: 24px;
        }

        .login-footer p {
            color: #6b7280;
            font-size: 13px;
        }

        /* Decorative elements */
        .decoration {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .decoration-1 {
            width: 100px;
            height: 100px;
            top: -30px;
            right: -30px;
        }

        .decoration-2 {
            width: 60px;
            height: 60px;
            bottom: -20px;
            left: -20px;
        }

        /* Icon wrapper */
        .icon-wrapper {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Filament form overrides */
        .fi-fo-field-wrp {
            margin-bottom: 20px !important;
        }

        .fi-fo-input {
            width: 100%;
            padding: 14px 16px !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            font-size: 15px !important;
            transition: all 0.3s ease !important;
            outline: none !important;
            background: #f9fafb !important;
        }

        .fi-fo-input:focus {
            border-color: #f59e0b !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
        }

        .fi-fo-label {
            color: #374151 !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            margin-bottom: 8px !important;
        }

        .fi-btn {
            width: 100%;
            padding: 16px !important;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            color: white !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4) !important;
        }

        .fi-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5) !important;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .fi-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fi-checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #f59e0b;
            cursor: pointer;
        }

        .fi-checkbox-label {
            color: #6b7280 !important;
            font-size: 14px !important;
        }

        .fi-section {
            margin-bottom: 0 !important;
        }

        .fi-actions {
            margin-top: 24px !important;
            padding-top: 0 !important;
        }

        .grid {
            gap: 24px !important;
        }
    </style>

    <div class="custom-login-page">
        <div class="login-container">
            <div class="login-header">
                <div class="decoration decoration-1"></div>
                <div class="decoration decoration-2"></div>
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1>Perpustakaan Sekolah</h1>
                <p>Sistem Manajemen Perpustakaan</p>
            </div>

            <div class="login-body">
                {{ $this->content }}

                <div class="login-footer">
                    <p>Silakan login dengan akun Anda untuk mengakses sistem</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
