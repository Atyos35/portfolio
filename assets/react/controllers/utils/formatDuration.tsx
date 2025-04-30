export function formatDuration(duration?: {
    y: number;
    m: number;
    d: number;
  }): string {
    if (!duration) return '';
  
    const parts: string[] = [];
  
    if (duration.y) parts.push(`${duration.y} ${duration.y > 1 ? 'ans' : 'an'}`);
    if (duration.m) parts.push(`${duration.m} mois`);
  
    if (parts.length === 0) return '0 jour';
  
    const last = parts.pop();
    return parts.length ? `${parts.join(', ')} et ${last}` : last!;
  }