import { useMutation, useQueryClient } from '@tanstack/react-query'
import type { ForgotPassInput } from '../../../types';
import { FetchAuth } from '../../authApi';
import useNotificationManager from '../../../../Utils/Components/Notification/hooks/useNotificationManager';

const useMutationForgotPass = () => {
    const client = useQueryClient();
    const {notify} = useNotificationManager();    
    const mutation = useMutation({
        mutationKey: ['reset-pass'],
        mutationFn: async (data: ForgotPassInput) => await FetchAuth('forgot-password', data),
        onSuccess: () => {
            client.invalidateQueries({queryKey: ['reset-pass']})
            if(mutation.data && mutation.data.success){
                notify(mutation.data.message, 'warning', true)
                return;
            }             
        },
        onError: (error:string) => {
            notify(error, 'error')
        }
    });
  return mutation;    
  
}

export default useMutationForgotPass;