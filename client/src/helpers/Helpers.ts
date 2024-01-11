import Swal from 'sweetalert2';

// wrapper for sweetalert2 to make button colors consistent
export function SwalFire(title: string, text: string, icon: any): void {
    Swal.fire({
        title,
        text,
        icon,
        cancelButtonColor: '#da8f20',
        confirmButtonColor: '#eea435',
    });
}