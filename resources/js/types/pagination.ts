// Структурная обёртка постраничного ответа Laravel/Spatie Data (data + links + meta).
// Сам RecipeData не дублируется — приходит из автогенерации generated.d.ts.
export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<TData> {
    data: TData[];
    links: PaginationLink[];
    meta: {
        current_page: number;
        first_page_url: string | null;
        from: number | null;
        last_page: number;
        last_page_url: string | null;
        next_page_url: string | null;
        path: string;
        per_page: number;
        prev_page_url: string | null;
        to: number | null;
        total: number;
    };
}
