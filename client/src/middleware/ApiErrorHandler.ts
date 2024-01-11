import { AxiosError } from 'axios';
import { type ApiResponse } from '../types/ApiResponse';
import { SwalFire } from '../helpers/Helpers';

export function ApiErrorHandler<T>(error: AxiosError): void {
    if (error.response) {
        const { status } = error.response;
        const data = error.response.data as ApiResponse<T>;
        switch (status) {
            case 401:
                SwalFire(data?.message || 'Unauthorized', '', 'error');
                break;
            case 404:
                SwalFire(data?.message || 'Not found', '', 'error');
                break;
            case 422:
                SwalFire(data?.message || 'Validation error', '', 'error');
                break;
            case 500:
                SwalFire(data?.message || 'Internal server error', '', 'error');
                break;
            default:
                SwalFire(data?.message || 'An error occurred', '', 'error');
                break;
        }
    } else {
        SwalFire('An error occurred', '', 'error');
    }
}
