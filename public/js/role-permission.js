function userHasRole(roles) {
    const userRole = document.querySelector('meta[name="user-role"]').getAttribute('content');
    
    if (typeof roles === 'string') {
        roles = [roles];
    }
    
    return roles.includes(userRole);
}

function showElementByRole(element, roles = ['Developer', 'super-admin']) {
    if (userHasRole(roles)) {
        element.style.display = '';
    } else {
        element.style.display = 'none';
    }
}

// Contoh penggunaan:
document.addEventListener('DOMContentLoaded', function() {
    // Sembunyikan tombol Add dan Export jika bukan Developer atau super-admin
    const addButtons = document.querySelectorAll('.add-button, .export-button, .import-button');
    addButtons.forEach(button => {
        showElementByRole(button);
    });
    
    // Sembunyikan kolom action pada tabel jika bukan Developer atau super-admin
    const actionColumns = document.querySelectorAll('.action-column');
    actionColumns.forEach(column => {
        showElementByRole(column);
    });
});