<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة USB</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-primary:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .login-footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        .password-toggle {
            cursor: pointer;
            background-color: #f8f9fa;
            border-left: none;
        }
        .focused {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-usb fa-3x mb-3"></i>
                <h3 class="fw-bold">نظام إدارة USB</h3>
                <p class="mb-0">مرحباً بعودتك، يرجى تسجيل الدخول</p>
            </div>

            <div class="login-body">
                <form id="loginForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="ادخل بريدك الإلكتروني" required>
                        </div>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="ادخل كلمة المرور" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                        <span id="loginText">
                            <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                        </span>
                        <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </span>
                    </button>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            تذكرني
                        </label>
                    </div>
                </form>

                <div id="alertContainer"></div>
            </div>

            <div class="login-footer">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    نظام آمن لإدارة ملفات USB
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // إذا كان هناك رسالة تسجيل خروج، مسح localStorage
            @if(session('logout'))
                localStorage.removeItem('access_token');
                localStorage.removeItem('user');
                showAlert('تم تسجيل الخروج بنجاح', 'info');
            @endif

            // التحقق من التوكن الموجود
            const token = localStorage.getItem('access_token');
            if (token) {
                checkTokenAndRedirect(token);
            }

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                performLogin();
            });
            
            // إدخال بالزر Enter
            $('#email, #password').on('keypress', function(e) {
                if (e.which === 13) {
                    performLogin();
                }
            });
            
            // إضافة تأثيرات للحقول
            addInputEffects();
        });
        
        function checkTokenAndRedirect(token) {
            showAlert('جاري التحقق من الجلسة الحالية...', 'info');
            
            $.ajax({
                url: '/api/check-token',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('تم العثور على جلسة نشطة! جاري التوجيه...', 'success');
                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 1000);
                    } else {
                        localStorage.removeItem('access_token');
                        localStorage.removeItem('user');
                        showAlert('انتهت الجلسة، يرجى تسجيل الدخول مرة أخرى', 'warning');
                    }
                },
                error: function(xhr) {
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('user');
                    
                    if (xhr.status === 401) {
                        showAlert('انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى', 'warning');
                    } else {
                        showAlert('خطأ في التحقق من الجلسة', 'danger');
                    }
                }
            });
        }
        
        function performLogin() {
            // إعادة تعيين رسائل الخطأ
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#alertContainer').empty();
            
            // التحقق من صحة البيانات الأساسية
            const email = $('#email').val().trim();
            const password = $('#password').val();
            
            if (!email) {
                $('#email').addClass('is-invalid');
                $('#emailError').text('يرجى إدخال البريد الإلكتروني');
                $('#email').focus();
                return;
            }
            
            if (!isValidEmail(email)) {
                $('#email').addClass('is-invalid');
                $('#emailError').text('يرجى إدخال بريد إلكتروني صحيح');
                $('#email').focus();
                return;
            }
            
            if (!password) {
                $('#password').addClass('is-invalid');
                $('#passwordError').text('يرجى إدخال كلمة المرور');
                $('#password').focus();
                return;
            }
            
            if (password.length < 6) {
                $('#password').addClass('is-invalid');
                $('#passwordError').text('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
                $('#password').focus();
                return;
            }
            
            // عرض حالة التحميل
            $('#loginBtn').prop('disabled', true);
            $('#loginText').addClass('d-none');
            $('#loginSpinner').removeClass('d-none');
            
            // جمع بيانات النموذج
            const formData = {
                email: email,
                password: password
            };
            
            // إرسال طلب تسجيل الدخول
            $.ajax({
                url: '/api/login',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // حفظ التوكن في localStorage
                        localStorage.setItem('access_token', response.data.access_token);
                        localStorage.setItem('user', JSON.stringify(response.data.user));
                        
                        // عرض رسالة النجاح
                        showAlert('تم تسجيل الدخول بنجاح! جاري التوجيه إلى لوحة التحكم...', 'success');
                        
                        // التوجيه إلى لوحة التحكم بعد تأخير بسيط
                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 1500);
                    } else {
                        showAlert(response.message || 'حدث خطأ أثناء تسجيل الدخول', 'danger');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    
                    if (xhr.status === 422) {
                        // معالجة أخطاء التحقق
                        if (response.errors) {
                            for (const field in response.errors) {
                                $(`#${field}`).addClass('is-invalid');
                                $(`#${field}Error`).text(response.errors[field][0]);
                            }
                        }
                    } else if (xhr.status === 404 || xhr.status === 401) {
                        showAlert('البريد الإلكتروني أو كلمة المرور غير صحيحة', 'danger');
                    } else if (xhr.status === 500) {
                        showAlert('حدث خطأ في الخادم، يرجى المحاولة لاحقاً', 'danger');
                    } else {
                        showAlert(response?.message || 'حدث خطأ غير متوقع', 'danger');
                    }
                },
                complete: function() {
                    // إعادة تعيين حالة الزر
                    $('#loginBtn').prop('disabled', false);
                    $('#loginText').removeClass('d-none');
                    $('#loginSpinner').addClass('d-none');
                }
            });
        }
        
        function togglePassword() {
            const passwordInput = $('#password');
            const passwordIcon = $('#passwordIcon');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function addInputEffects() {
            $('.form-control').on('focus', function() {
                $(this).closest('.input-group').addClass('focused');
            });
            
            $('.form-control').on('blur', function() {
                $(this).closest('.input-group').removeClass('focused');
            });
        }
        
        function showAlert(message, type) {
            const alertClass = {
                'success': 'alert-success',
                'danger': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            }[type] || 'alert-info';
            
            const icon = {
                'success': 'fa-check-circle',
                'danger': 'fa-exclamation-triangle',
                'warning': 'fa-exclamation-circle',
                'info': 'fa-info-circle'
            }[type] || 'fa-info-circle';
            
            $('#alertContainer').html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas ${icon} me-2 fs-5"></i>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            // إزالة التنبيه تلقائياً بعد 5 ثواني
            setTimeout(() => {
                const alert = $('#alertContainer .alert');
                if (alert.length) {
                    alert.alert('close');
                }
            }, 5000);
        }
        
        // إظهار إشعار ترحيبي عند التحميل
        setTimeout(() => {
            if ($('#alertContainer').children().length === 0) {
                showAlert('مرحباً! يرجى تسجيل الدخول للوصول إلى النظام', 'info');
            }
        }, 1000);
    </script>
</body>
</html>