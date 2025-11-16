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
        onSuccess:(data)=>{
            client.invalidateQueries({queryKey: ['register']})
            if(data?.message){
                notify(data?.message, 'success');    
                return;            
            }       
            notify("An error occured!", 'error');   
        },
        onError: (data)=>{
            client.invalidateQueries({queryKey: ['register']})
            if(data)
                notify(data?.message, 'error');                                      
        }
    })
  return mutation
}
