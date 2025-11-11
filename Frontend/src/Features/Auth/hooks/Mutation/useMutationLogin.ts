import { useMutation, useQueryClient } from "@tanstack/react-query"
import { FetchAuth, type LoginResponse } from "../../authApi"
import type { LoginInputs } from "../../../types"
import useNotificationManager from "../../../../Utils/Components/Notification/hooks/useNotificationManager"
import { useNavigate } from "react-router"



const useMutationLogin = () => {
  const client = useQueryClient();
  const {notify} = useNotificationManager();
  const navigate = useNavigate();
  const mutation =  useMutation<LoginResponse, void, LoginInputs>({
        mutationKey: ['login'],
        mutationFn: (data: LoginInputs) => FetchAuth('login', data),
        onSuccess: () => {
            client.invalidateQueries({queryKey: ['login']});
            if(mutation.data)
              if(mutation.data.success){
                notify(mutation.data.message, 'success');
                navigate('/home');
              }            
              notify('An error occured', 'error')           
        }, 
        onError: (error)=> {
          notify(mutation.data?.message || 'An error occured!', "error");
          console.error(error);          
        }
    })
    return mutation
}

export default useMutationLogin