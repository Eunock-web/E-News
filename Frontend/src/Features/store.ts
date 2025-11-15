import { create } from "zustand"
import { apiFetch } from "../Infrastructure/api";
import { useQuery, useQueryClient } from "@tanstack/react-query";
import useNotificationManager from "../Utils/Components/Notification/hooks/useNotificationManager";

type Categories = {
    categories: string[] | { message: string },
}

export const useFetchCategories = (enabled: boolean) => {
    const {notify} = useNotificationManager();
    const client = useQueryClient();
    const { data, isSuccess, isError } = useQuery<string[] | {message: string}>({
        queryKey: ['getCategories'],
        queryFn: async () => {
            try {
                const response = await apiFetch('news/categories');
                client.invalidateQueries({queryKey: ['getCategories']})
                return response;
            } catch (error) {
                notify('Error occurred while fetching data!', 'error');
                throw error;
            }
        },
        retry: false,
        refetchOnMount:false,
        retryOnMount:false,
        enabled: enabled
    });

    return { data, isSuccess, isError };
};

const useStoreDefault = create<Categories>(() => ({
    categories: [],
}));

export default useStoreDefault;