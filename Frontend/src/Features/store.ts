import { create } from "zustand"
import { apiFetch } from "../Infrastructure/api";
import { useQuery, useQueryClient } from "@tanstack/react-query";

type Categories = {
    categories: string[],
    getCategories: () => Promise<string[]>
}

function useStoreDefault() {
    const client = useQueryClient();
    const categories = create<Categories>((set)=> ({
    categories: [''],
    getCategories: async () => {
        const {isSuccess, data} = useQuery<string[]>({
            queryKey:['getCategories'],
            queryFn: () => apiFetch('news/categories')        
        }, client)
            if(isSuccess && data)
                set({categories: data})
            set            
            return ['']
        }
      }),
    );
    return categories
}

export default useStoreDefault;