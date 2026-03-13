export function unwrapResponse(payload) {
    return payload?.data ?? payload;
}

export function unwrapList(payload) {
    const data = unwrapResponse(payload);

    if (Array.isArray(data)) {
        return { items: data, meta: null };
    }

    if (Array.isArray(data?.data)) {
        return {
            items: data.data,
            meta: {
                current_page: data.current_page,
                last_page: data.last_page,
                per_page: data.per_page,
                total: data.total,
            },
        };
    }

    return { items: [], meta: null };
}

export function formatDate(value) {
    if (!value) {
        return '-';
    }

    try {
        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(new Date(value));
    } catch {
        return value;
    }
}

export function formatCurrency(value) {
    if (!value) {
        return 'Negotiable';
    }

    const number = Number(String(value).replace(/[^\d.-]/g, ''));

    if (Number.isNaN(number)) {
        return value;
    }

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(number);
}

export function imageUrl(path) {
    if (!path) {
        return null;
    }

    if (path.startsWith('http')) {
        return path;
    }

    return `/storage/${path}`;
}

export function excerpt(value, fallback = 'Data tersedia dari backend Laravel.') {
    if (!value) {
        return fallback;
    }

    const text = String(value).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
    return text.length > 160 ? `${text.slice(0, 157)}...` : text;
}
