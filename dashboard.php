<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // ما في جلسة، رجع لصفحة تسجيل الدخول
    header("Location: index.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body{
         background:linear-gradient(to right, pink,#0000FF,pink,purple);

        }
        .sidebar {
            min-height: 100vh;
            width: 180px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar .nav-link {
            color: black;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .custom-card {
            max-width: 800px;
            width: 100%;
            min-height: 400px;
        }
        .note-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- الشريط الجانبي -->
        <div class="sidebar d-none d-md-block p-3">
            <div class="text-center mb-4">
                <img src="assets/images/17576035.png" class="w-50 d-block mx-auto" alt="StickyMicky Logo">
                <h3 class="text-white fs-6 mt-3">StickyMicky</h3>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="#" onclick="showAllNotes()"><i class="bi bi-sticky me-2"></i>ملاحظاتي</a>
                <h6 class="text-white mt-3 mb-2">أقسامي</h6>
                <div id="sectionsList"></div>
                <div class="dropdown mt-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-circle me-2"></i>إضافة
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="addDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addSectionModal">إضافة قسم</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addNoteModal">إضافة ملاحظة</a></li>
                    </ul>
                </div>
                <a class="nav-link mt-3" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person-circle me-2"></i>الملف الشخصي</a>
            </nav>
        </div>
        <!-- زر فتح الشريط الجانبي على الشاشات الصغيرة -->
        <button class="btn btn-primary d-md-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
            <i class="bi bi-list"></i>
        </button>
        <!-- Offcanvas للشاشات الصغيرة -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarLabel">StickyMicky</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="showAllNotes()"><i class="bi bi-sticky me-2"></i>ملاحظاتي</a>
                    <h6 class="mt-3 mb-2">أقسامي</h6>
                    <div id="sectionsListMobile"></div>
                    <div class="dropdown mt-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle me-2"></i>إضافة
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="addDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addSectionModal">إضافة قسم</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addNoteModal">إضافة ملاحظة</a></li>
                        </ul>
                    </div>
                    <a class="nav-link mt-3" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person-circle me-2"></i>الملف الشخصي</a>
                </nav>
            </div>
        </div>
        <!-- المحتوى الرئيسي -->
        <div class="container my-5 flex-grow-1">
            <div class="card p-4 shadow-lg custom-card mx-auto rounded-3">
                <h2 class="text-center mb-4 fs-3" id="contentTitle">ملاحظاتي</h2>
                <div id="contentContainer" class="row"></div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة قسم -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSectionModalLabel">إضافة قسم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sectionName" class="form-label">اسم القسم</label>
                        <input type="text" class="form-control" id="sectionName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="addSection()">إضافة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة ملاحظة -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNoteModalLabel">إضافة ملاحظة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="noteText" class="form-label">نص الملاحظة</label>
                        <textarea class="form-control" id="noteText" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="noteSection" class="form-label">القسم</label>
                        <select class="form-select" id="noteSection" required></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="addNote()">إضافة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لتعديل ملاحظة -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoteModalLabel">تعديل الملاحظة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNoteText" class="form-label">نص الملاحظة</label>
                        <textarea class="form-control" id="editNoteText" rows="4" required></textarea>
                    </div>
                    <input type="hidden" id="editNoteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="updateNote()">حفظ التعديلات</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal للملف الشخصي -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">الملف الشخصي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profileName" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="profileName" required>
                    </div>
                    <div class="mb-3">
                        <label for="profileEmail" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="profileEmail" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveProfile()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // تحميل البيانات من LocalStorage
        let sections = JSON.parse(localStorage.getItem('sections')) || [];
        let notes = JSON.parse(localStorage.getItem('notes')) || [];
        let profile = JSON.parse(localStorage.getItem('profile')) || { name: 'المستخدم', email: 'user@example.com' };

        // عرض الأقسام والملاحظات عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', () => {
            showAllNotes();
            updateSectionList();
            updateSectionSelect();
            loadProfile();
        });

        // إضافة قسم جديد
        function addSection() {
            const sectionName = document.getElementById('sectionName').value.trim();
            if (sectionName) {
                sections.push(sectionName);
                localStorage.setItem('sections', JSON.stringify(sections));
                updateSectionList();
                updateSectionSelect();
                document.getElementById('sectionName').value = '';
                new bootstrap.Modal(document.getElementById('addSectionModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'تمت الإضافة',
                    text: 'تم إضافة القسم بنجاح!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال اسم القسم!'
                });
            }
        }

        // إضافة ملاحظة جديدة
        function addNote() {
            const noteText = document.getElementById('noteText').value.trim();
            const noteSection = document.getElementById('noteSection').value;
            if (noteText && noteSection) {
                const note = {
                    id: Date.now(),
                    text: noteText,
                    section: noteSection
                };
                notes.push(note);
                localStorage.setItem('notes', JSON.stringify(notes));
                showAllNotes();
                document.getElementById('noteText').value = '';
                document.getElementById('noteSection').value = '';
                new bootstrap.Modal(document.getElementById('addNoteModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'تمت الإضافة',
                    text: 'تم إضافة الملاحظة بنجاح!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال نص الملاحظة واختيار قسم!'
                });
            }
        }

        // تعديل ملاحظة
        function editNote(id) {
            const note = notes.find(n => n.id === id);
            if (note) {
                document.getElementById('editNoteText').value = note.text;
                document.getElementById('editNoteId').value = id;
                new bootstrap.Modal(document.getElementById('editNoteModal')).show();
            }
        }

        // حفظ التعديلات على ملاحظة
        function updateNote() {
            const id = parseInt(document.getElementById('editNoteId').value);
            const newText = document.getElementById('editNoteText').value.trim();
            if (newText) {
                const note = notes.find(n => n.id === id);
                if (note) {
                    note.text = newText;
                    localStorage.setItem('notes', JSON.stringify(notes));
                    showAllNotes();
                    new bootstrap.Modal(document.getElementById('editNoteModal')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التعديل',
                        text: 'تم تعديل الملاحظة بنجاح!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال نص الملاحظة!'
                });
            }
        }

        // حذف ملاحظة
        async function deleteNote(id) {
            const result = await Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'هل تريد حذف هذه الملاحظة؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفها!',
                cancelButtonText: 'إلغاء'
            });
            if (result.isConfirmed) {
                notes = notes.filter(n => n.id !== id);
                localStorage.setItem('notes', JSON.stringify(notes));
                showAllNotes();
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: 'تم حذف الملاحظة بنجاح!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        // عرض جميع الملاحظات
        function showAllNotes() {
            document.getElementById('contentTitle').textContent = 'ملاحظاتي';
            const container = document.getElementById('contentContainer');
            container.innerHTML = '';
            if (notes.length) {
                notes.forEach(note => {
                    const noteDiv = document.createElement('div');
                    noteDiv.className = 'col-12 mb-3';
                    noteDiv.innerHTML = `
                        <div class="note-card d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0">${note.text}</p>
                                <small class="text-muted">القسم: ${note.section}</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-warning me-2" onclick="editNote(${note.id})">تعديل</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteNote(${note.id})">حذف</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(noteDiv);
                });
            } else {
                container.innerHTML = '<p class="text-muted text-center">لا توجد ملاحظات</p>';
            }
        }

        // عرض ملاحظات قسم معين
        function showSectionNotes(section) {
            document.getElementById('contentTitle').textContent = `ملاحظات القسم: ${section}`;
            const container = document.getElementById('contentContainer');
            container.innerHTML = '';
            const sectionNotes = notes.filter(note => note.section === section);
            if (sectionNotes.length) {
                sectionNotes.forEach(note => {
                    const noteDiv = document.createElement('div');
                    noteDiv.className = 'col-12 mb-3';
                    noteDiv.innerHTML = `
                        <div class="note-card d-flex justify-content-between align-items-center">
                            <p class="mb-0">${note.text}</p>
                            <div>
                                <button class="btn btn-sm btn-warning me-2" onclick="editNote(${note.id})">تعديل</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteNote(${note.id})">حذف</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(noteDiv);
                });
            } else {
                container.innerHTML = '<p class="text-muted text-center">لا توجد ملاحظات في هذا القسم</p>';
            }
        }

        // تحديث قائمة الأقسام في الشريط الجانبي
        function updateSectionList() {
            const list = document.getElementById('sectionsList');
            const listMobile = document.getElementById('sectionsListMobile');
            list.innerHTML = '';
            listMobile.innerHTML = '';
            sections.forEach(section => {
                const link = document.createElement('a');
                link.className = 'nav-link';
                link.href = '#';
                link.innerHTML = `<i class="bi bi-folder me-2"></i>${section}`;
                link.onclick = () => showSectionNotes(section);
                list.appendChild(link);
                const linkMobile = link.cloneNode(true);
                listMobile.appendChild(linkMobile);
            });
        }

        // تحديث قائمة الأقسام في القائمة المنسدلة
        function updateSectionSelect() {
            const select = document.getElementById('noteSection');
            select.innerHTML = '<option value="" disabled selected>اختر قسمًا</option>';
            sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section;
                option.textContent = section;
                select.appendChild(option);
            });
        }

        // تحميل الملف الشخصي
        function loadProfile() {
            document.getElementById('profileName').value = profile.name;
            document.getElementById('profileEmail').value = profile.email;
        }

        // حفظ الملف الشخصي
        function saveProfile() {
            const name = document.getElementById('profileName').value.trim();
            const email = document.getElementById('profileEmail').value.trim();
            if (name && email) {
                profile = { name, email };
                localStorage.setItem('profile', JSON.stringify(profile));
                new bootstrap.Modal(document.getElementById('profileModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحفظ',
                    text: 'تم حفظ الملف الشخصي بنجاح!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال الاسم والبريد الإلكتروني!'
                });
            }
        }
    </script>
</body>
</html>