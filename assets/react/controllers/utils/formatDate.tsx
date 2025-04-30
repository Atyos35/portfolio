export function formatDateInput(value?: Date | string): string {
    if (value instanceof Date) {
        return value.toISOString().split('T')[0];
    } else if (typeof value === 'string') {
        return value.split('T')[0];
    }
    return '';
}