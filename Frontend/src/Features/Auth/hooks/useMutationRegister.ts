import { useMutation } from "@tanstack/react-query"
import { FetchRegister } from "../authApi"
import type { RegisterInputs } from "../../types"
import useNotificationManager from "../../../Utils/Components/Notification/hooks/useNotificationManager"

export type RegisterResponse = {

}
export const useMutationRegister = () => {
    const {notify} = useNotificationManager();
    const mutation = useMutation({
        mutationKey:['register'],
        mutationFn: (data:RegisterInputs)=>FetchRegister(data),
        onSuccess:()=>{
            if(mutation.data?.message){
                notify(mutation.data?.message, 'success');                
            }                
        },
        onError: ()=>{
            if(mutation.data)
                notify(mutation.data?.message, 'error')
        }
    })
  return mutation
}
