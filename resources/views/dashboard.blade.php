<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة USB</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 30px;
        }
        
        .action-card {
            background: white;
            border-radius: 15px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
            cursor: pointer;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .action-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .content-container {
            padding: 30px;
        }
        
        .file-preview {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .file-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .file-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        
        .file-name {
            word-break: break-word;
            line-height: 1.4;
            height: 2.8em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            font-size: 0.9rem;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #dc3545;
            color: white;
        }
        
        .upload-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>
    
    <!-- شريط التنقل العلوي -->
    <nav class="navbar-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold text-primary">لوحة التحكم - نظام إدارة USB</h4>
                </div>
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-2" id="userAvatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="me-3">
                        <span class="fw-bold d-block" id="userName">مستخدم</span>
                        <small class="text-muted" id="userEmail">تحميل...</small>
                    </div>
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-1"></i>تسجيل الخروج
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- محتوى الصفحة -->
    <div class="content-container">
        <!-- تحقق المصادقة -->
        <div id="authCheck" class="loading-spinner">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">جاري التحقق من المصادقة...</span>
                </div>
                <h5 class="text-primary">جاري التحقق من المصادقة...</h5>
                <p class="text-muted">يرجى الانتظار أثناء تحميل البيانات</p>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div id="dashboardContent" class="d-none">
            <!-- رسائل التنبيه -->
            <div id="alertContainer" class="mb-4"></div>

            <!-- بانتر الترحيب -->
            <div class="welcome-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-2" id="welcomeMessage">مرحباً بعودتك!</h3>
                        <p class="mb-0" id="welcomeSubtitle">هنا يمكنك إدارة جميع ملفات USB الخاصة بك</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-usb fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- قسم رفع الملفات -->
            <div class="upload-section">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-2">رفع ملف جديد</h5>
                        <p class="text-muted mb-0">اختر ملف لرفعه إلى الفلاشة مباشرة</p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <input type="file" id="fileInput" class="form-control" style="display: none;">
                            <button class="btn btn-primary flex-fill" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-upload me-2"></i>اختر ملف
                            </button>
                            <button class="btn btn-success" onclick="uploadSelectedFile()" id="uploadBtn">
                                <i class="fas fa-cloud-upload-alt me-2"></i>رفع
                            </button>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted" id="selectedFileName">لم يتم اختيار أي ملف</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- العمليات الرئيسية -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold text-primary mb-0">الملفات الموجودة على الفلاشة</h4>
                        <button class="btn btn-outline-primary btn-sm" onclick="loadFiles()">
                            <i class="fas fa-sync-alt me-1"></i>تحديث القائمة
                        </button>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="action-card" onclick="loadFiles()">
                        <div class="action-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">تحديث</h5>
                        <p class="text-muted small">تحديث قائمة الملفات</p>
                        <div class="mt-2">
                            <span class="badge bg-info" id="statusBadge">جاري التحميل...</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="action-card" onclick="document.getElementById('fileInput').click()">
                        <div class="action-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <h5 class="fw-bold mb-2">رفع ملف</h5>
                        <p class="text-muted small">تحميل ملف جديد</p>
                        <div class="mt-2">
                            <span class="badge bg-success">مباشر</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-file"></i>
                        </div>
                        <h5 class="fw-bold mb-2" id="totalFilesText">0 ملف</h5>
                        <p class="text-muted small">إجمالي الملفات</p>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-hdd"></i>
                        </div>
                        <h5 class="fw-bold mb-2" id="connectionStatus">--</h5>
                        <p class="text-muted small">حالة الاتصال</p>
                    </div>
                </div>
            </div>

            <!-- الملفات الموجودة -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div id="filesLoading" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">جاري التحميل...</span>
                                </div>
                                <p class="mt-2 text-muted">جاري تحميل الملفات...</p>
                            </div>
                            
                            <div id="noFilesMessage" class="text-center py-5 d-none">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد ملفات</h5>
                                <p class="text-muted">لم يتم العثور على أي ملفات في الفلاشة</p>
                                <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-upload me-2"></i>رفع ملف أول
                                </button>
                            </div>
                            
                            <div id="filesList" class="row d-none">
                                <!-- الملفات سيتم عرضها هنا -->
                            </div>

                            <div id="filesError" class="text-center py-5 d-none">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">خطأ في تحميل الملفات</h5>
                                <p class="text-muted" id="errorMessage">حدث خطأ أثناء تحميل الملفات</p>
                                <button class="btn btn-warning" onclick="loadFiles()">
                                    <i class="fas fa-redo me-2"></i>إعادة المحاولة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            checkAuthentication();
            setupFileInput();
        });
        
        function setupFileInput() {
    const fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (file) {
            if (file.size > maxSize) {
                $('#selectedFileName').text(`⚠️ حجم الملف (${formatFileSize(file.size)}) أكبر من الحد المسموح (5MB)`);
                $('#uploadBtn').prop('disabled', true);
                showAlert('حجم الملف يتجاوز الحد الأقصى (5MB)', 'warning');
            } else {
                $('#selectedFileName').text(`تم اختيار: ${file.name} (${formatFileSize(file.size)})`);
                $('#uploadBtn').prop('disabled', false);
            }
        } else {
            $('#selectedFileName').text('لم يتم اختيار أي ملف');
            $('#uploadBtn').prop('disabled', true);
        }
    });
}

        
        function uploadSelectedFile() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!file) {
        showAlert('يرجى اختيار ملف أولاً', 'warning');
        return;
    }

    if (file.size > maxSize) {
        showAlert('⚠️ حجم الملف يتجاوز الحد الأقصى (5MB)', 'danger');
        return;
    }

    // عرض حالة التحميل
    $('#uploadBtn').prop('disabled', true);
    $('#uploadBtn').html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...');

    const formData = new FormData();
    formData.append('file', file);

    $.ajax({
        url: '/api/usb/upload',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function(response) {
            if (response.success) {
                showAlert('تم رفع الملف بنجاح: ' + file.name, 'success');
                fileInput.value = '';
                $('#selectedFileName').text('لم يتم اختيار أي ملف');
                loadFiles();
            } else {
                showAlert(response.message || 'فشل في رفع الملف', 'danger');
            }
        },
        error: function(xhr) {
            showAlert('فشل في رفع الملف: ' + (xhr.responseJSON?.message || 'خطأ في الخادم'), 'danger');
        },
        complete: function() {
            $('#uploadBtn').prop('disabled', false);
            $('#uploadBtn').html('<i class="fas fa-cloud-upload-alt me-2"></i>رفع');
        }
    });
}

        function checkAuthentication() {
            const token = localStorage.getItem('access_token');
            
            if (!token) {
                showAlert('يجب تسجيل الدخول أولاً', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
                return;
            }
            
            // التحقق من صحة التوكن
            $.ajax({
                url: '/api/check-token',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.success) {
                        // التوكن صالح، عرض المحتوى
                        $('#authCheck').addClass('d-none');
                        $('#dashboardContent').removeClass('d-none');
                        
                        // تحميل بيانات المستخدم والملفات
                        loadUserData();
                        loadFiles();
                    } else {
                        handleAuthError();
                    }
                },
                error: function(xhr) {
                    handleAuthError();
                }
            });
        }
        
        function loadUserData() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            
            if (user.name) {
                $('#userName').text(user.name);
                $('#userEmail').text(user.email);
                
                // إنشاء الأحرف الأولى من الاسم للصورة الرمزية
                const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase();
                $('#userAvatar').html(initials || '<i class="fas fa-user"></i>');
                
                // تحديث رسالة الترحيب
                $('#welcomeMessage').text(`مرحباً ${user.name}!`);
                const hour = new Date().getHours();
                let greeting = 'مساء الخير';
                if (hour < 12) greeting = 'صباح الخير';
                else if (hour < 18) greeting = 'مساء الخير';
                $('#welcomeSubtitle').text(`${greeting}! - نظام إدارة ملفات USB`);
            }
        }
        
        function loadFiles() {
            // إظهار حالة التحميل
            $('#filesLoading').removeClass('d-none');
            $('#noFilesMessage').addClass('d-none');
            $('#filesList').addClass('d-none');
            $('#filesError').addClass('d-none');
            
            // جلب بيانات الملفات
            $.ajax({
                url: '/api/usb/files',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    console.log('API Response:', response);
                    $('#filesLoading').addClass('d-none');
                    
                    if (response.success && response.data) {
                        // الحصول على الملفات من الهيكل الصحيح
                        let files = [];
                        
                        // محاولة الحصول على الملفات من الهياكل المختلفة
                        if (response.data.files && Array.isArray(response.data.files)) {
                            // الهيكل: data.files
                            files = response.data.files;
                        } else if (response.data.files && response.data.files.files && Array.isArray(response.data.files.files)) {
                            // الهيكل: data.files.files
                            files = response.data.files.files;
                        } else if (Array.isArray(response.data)) {
                            // الهيكل: data
                            files = response.data;
                        }
                        
                        console.log('Extracted files:', files);
                        
                        if (files.length > 0) {
                            displayFiles(files);
                            $('#statusBadge').text('متصل').removeClass('bg-warning').addClass('bg-success');
                            $('#connectionStatus').text('متصل');
                        } else {
                            $('#noFilesMessage').removeClass('d-none');
                            $('#totalFilesText').text('0 ملف');
                            $('#statusBadge').text('لا توجد ملفات').removeClass('bg-success').addClass('bg-warning');
                            $('#connectionStatus').text('لا توجد ملفات');
                        }
                    } else {
                        $('#noFilesMessage').removeClass('d-none');
                        $('#totalFilesText').text('0 ملف');
                        $('#statusBadge').text('خطأ في الاستجابة').removeClass('bg-success').addClass('bg-danger');
                        $('#connectionStatus').text('خطأ');
                    }
                },
                error: function(xhr) {
                    console.error('API Error:', xhr);
                    $('#filesLoading').addClass('d-none');
                    $('#filesError').removeClass('d-none');
                    $('#statusBadge').text('خطأ في الاتصال').removeClass('bg-success bg-warning').addClass('bg-danger');
                    $('#connectionStatus').text('غير متصل');
                    
                    const errorMessage = xhr.responseJSON?.message || 'حدث خطأ أثناء تحميل الملفات';
                    $('#errorMessage').text(errorMessage);
                }
            });
        }
        
        function displayFiles(files) {
            const filesList = $('#filesList');
            filesList.empty();
            
            console.log('Displaying files:', files);
            
            // تحديث عدد الملفات
            const fileCount = files.length;
            $('#totalFilesText').text(fileCount + ' ملف');
            
            files.forEach(file => {
                // إذا كان file عبارة عن string (اسم الملف فقط)
                const fileName = typeof file === 'string' ? file : file.name || file.filename;
                const fileSize = file.size ? formatFileSize(file.size) : '';
                const fileIcon = getFileIcon(fileName);
                
                filesList.append(`
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <div class="file-preview">
                            <div class="file-icon text-center">
                                <i class="${fileIcon}"></i>
                            </div>
                            <h6 class="file-name text-center mb-2">${fileName}</h6>
                            <div class="text-center text-muted small mb-3">
                                ${fileSize}
                            </div>
                            <div class="text-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="downloadFile('${fileName.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-download me-1"></i>تنزيل
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            });
            
            filesList.removeClass('d-none');
        }
        
        function getFileIcon(filename) {
            if (!filename) return 'fas fa-file text-primary';
            
            // التعامل مع الملفات التي لا تحتوي على امتداد
            const parts = filename.split('.');
            if (parts.length === 1) {
                // ملف بدون امتداد - اعتماداً على إذا كان مجلد أو ملف
                if (isLikelyFolder(filename)) {
                    return 'fas fa-folder text-warning';
                } else {
                    return 'fas fa-file text-secondary';
                }
            }
            
            const ext = parts.pop().toLowerCase();
            const iconMap = {
                // ملفات العرض التقديمي
                'ppt': 'fas fa-file-powerpoint text-warning',
                'pptx': 'fas fa-file-powerpoint text-warning',
                'pps': 'fas fa-file-powerpoint text-warning',
                'ppsx': 'fas fa-file-powerpoint text-warning',
                'pot': 'fas fa-file-powerpoint text-warning',
                'potx': 'fas fa-file-powerpoint text-warning',
                
                // ملفات PDF
                'pdf': 'fas fa-file-pdf text-danger',
                
                // ملفات Word
                'doc': 'fas fa-file-word text-primary',
                'docx': 'fas fa-file-word text-primary',
                'docm': 'fas fa-file-word text-primary',
                'dot': 'fas fa-file-word text-primary',
                'dotx': 'fas fa-file-word text-primary',
                
                // ملفات Excel
                'xls': 'fas fa-file-excel text-success',
                'xlsx': 'fas fa-file-excel text-success',
                'xlsm': 'fas fa-file-excel text-success',
                'xlt': 'fas fa-file-excel text-success',
                'xltx': 'fas fa-file-excel text-success',
                'csv': 'fas fa-file-csv text-success',
                
                // ملفات الصور
                'jpg': 'fas fa-file-image text-info',
                'jpeg': 'fas fa-file-image text-info',
                'png': 'fas fa-file-image text-info',
                'gif': 'fas fa-file-image text-info',
                'bmp': 'fas fa-file-image text-info',
                'svg': 'fas fa-file-image text-info',
                'webp': 'fas fa-file-image text-info',
                
                // ملفات الأرشيف
                'zip': 'fas fa-file-archive text-secondary',
                'rar': 'fas fa-file-archive text-secondary',
                '7z': 'fas fa-file-archive text-secondary',
                'tar': 'fas fa-file-archive text-secondary',
                'gz': 'fas fa-file-archive text-secondary',
                
                // ملفات النص
                'txt': 'fas fa-file-alt text-dark',
                'rtf': 'fas fa-file-alt text-dark',
                
                // ملفات الصوت
                'mp3': 'fas fa-file-audio text-primary',
                'wav': 'fas fa-file-audio text-primary',
                'ogg': 'fas fa-file-audio text-primary',
                'flac': 'fas fa-file-audio text-primary',
                
                // ملفات الفيديو
                'mp4': 'fas fa-file-video text-danger',
                'avi': 'fas fa-file-video text-danger',
                'mkv': 'fas fa-file-video text-danger',
                'mov': 'fas fa-file-video text-danger',
                'wmv': 'fas fa-file-video text-danger',
                'flv': 'fas fa-file-video text-danger',
                
                // ملفات النظام
                'inf': 'fas fa-cog text-secondary',
                'exe': 'fas fa-cog text-warning',
                'msi': 'fas fa-cog text-warning',
                'bat': 'fas fa-terminal text-dark',
                'cmd': 'fas fa-terminal text-dark'
            };
            
            return iconMap[ext] || 'fas fa-file text-primary';
        }
async function logout() {
    try {
        const token = localStorage.getItem('access_token');
        console.log('logout() called. token=', token);

        // تحقق سريع إن التوكن موجود
        if (!token) {
            console.warn('No access_token found in localStorage — redirecting to login anyway.');
            localStorage.removeItem('access_token');
            window.location.href = '/login.html';
            return;
        }

        const url = '/api/logout'; // تأكد أن هذا هو المسار الصحيح (قد يكون /logout حسب إعدادك)

        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            // اذا كنت تستخدم cookie-based sanctum بدل token-based فافتح السطر التالي:
            // credentials: 'include'
        });

        console.log('Logout response status:', res.status, res.statusText);

        // حالات شائعة
        if (res.status === 401) {
            console.warn('Server returned 401 — token invalid or already revoked');
        } else if (!res.ok && res.status !== 204) {
            // حاول قراءة رسالة الخطأ من الجسم إن وجدت
            let body = '';
            try { body = await res.text(); } catch(e) {}
            console.error('Logout failed:', res.status, body);
        } else {
            console.log('Logout OK');
        }
    } catch (err) {
        // ممكن يكون خطأ CORS أو خطأ شبكة
        console.error('Logout request error:', err);
    } finally {
        // تنظيف محلي وإعادة توجيه دائماً
        localStorage.removeItem('access_token');
        // إعادة التوجيه لصفحة تسجيل الدخول
        window.location.href = '/login';
    }
}

        function isLikelyFolder(filename) {
            const folderIndicators = [
                'System Volume Information', 'test1', 'test', 'fullfluteer', 
                'part_1', 'E2', 'finallll', 'part_! translate', 'nawras_23_9_part1',
                'part_4_eng'
            ];
            return folderIndicators.includes(filename) || 
                   !filename.includes('.') ||
                   filename.toLowerCase().includes('folder') ||
                   filename.toLowerCase().includes('directory');
        }
        
        function formatFileSize(bytes) {            
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        }
        
function downloadFile(filename) {
    showAlert(`جاري تنزيل الملف: ${filename}`, 'info');

    const token = localStorage.getItem('access_token');
    const url = `/api/usb/download/${encodeURIComponent(filename)}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('خطأ في تنزيل الملف');
        }
        return response.blob();
    })
    .then(blob => {
        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(blobUrl);
    })
    .catch(err => {
        showAlert('فشل في تنزيل الملف: ' + err.message, 'danger');
    });
}

        function handleAuthError() {
            showAlert('انتهت الجلسة، يرجى تسجيل الدخول مرة أخرى', 'warning');
            setTimeout(() => {
                localStorage.removeItem('access_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }, 3000);
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

        // تحديث تلقائي كل دقيقة
        setInterval(() => {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                const hour = new Date().getHours();
                let greeting = 'مساء الخير';
                if (hour < 12) greeting = 'صباح الخير';
                else if (hour < 18) greeting = 'مساء الخير';
                $('#welcomeSubtitle').text(`${greeting}! - نظام إدارة ملفات USB`);
            }
        }, 60000);
    </script>
</body>
</html>