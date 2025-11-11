import { useMutation, useQueryClient } from '@tanstack/react-query'
import type { ResetPassInputs } from '../../../types';
import { FetchAuth } from '../../authApi';
import useNotificationManager from '../../../../Utils/Components/Notification/hooks/useNotificationManager';

const useMutationResetPass = () => {
    const client = useQueryClient();
    const {notify} = useNotificationManager();    
    const mutation = useMutation({
        mutationKey: ['reset-pass'],
        mutationFn: async (data: ResetPassInputs) => await FetchAuth('reset-password', data),
        onSuccess: () => {
            client.invalidateQueries({queryKey: ['reset-pass']})
            if(mutation.data && mutation.data.success){
                notify('Password reset succesfully.', 'success');
                return;
            }             
        },
        onError: (error:string) => {
            notify(error, 'error')
        }
    });
  return mutation;

}

export default useMutationResetPass