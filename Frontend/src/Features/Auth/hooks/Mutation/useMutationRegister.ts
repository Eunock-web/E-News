import { useMutation, useQueryClient } from "@tanstack/react-query"
import { FetchAuth } from "../../authApi"
import type { RegisterInputs } from "../../../types"
import useNotificationManager from "../../../../Utils/Components/Notification/hooks/useNotificationManager"

export type RegisterResponse = {

}
export const useMutationRegister = () => {
    const client = useQueryClient();
    const {notify} = useNotificationManager();
    const mutation = useMutation({
        mutationKey:['register'],
        mutationFn: (data:RegisterInputs)=>FetchAuth('register', data),
        onSuccess:()=>{
            client.invalidateQueries({queryKey: ['register']})
            if(mutation.data?.message){
                notify(mutation.data?.message, 'success');    
                return;            
            }       
            notify("An error occured!", 'error');   
        },
        onError: ()=>{
            if(mutation.data)
                notify(mutation.data?.message, 'error');                                      
        }
    })
  return mutation
}
