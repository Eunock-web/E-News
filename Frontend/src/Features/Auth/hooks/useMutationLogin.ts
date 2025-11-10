import { useMutation } from "@tanstack/react-query"
import { FetchLogin, type LoginResponse } from "../authApi"
import type { LoginInputs } from "../../types"
import useNotificationManager from "../../../Utils/Components/Notification/hooks/useNotificationManager"
import { useNavigate } from "react-router"



const useMutationLogin = () => {
  const {notify} = useNotificationManager();
  const navigate = useNavigate();
  const mutation =  useMutation<LoginResponse, void, LoginInputs>({
        mutationKey: ['login'],
        mutationFn: (data: LoginInputs) => FetchLogin(data),
        onSuccess: () => {
            if(mutation.isSuccess)
              if(mutation.data.success){
                notify(mutation.data.message, 'success');
                navigate('/home');
              }
                
        }, 
        onError: (error)=> {
          notify(mutation.data?.message || 'An error occured!', "error");
          console.error(error);          
        }
    })
    return mutation
}

export default useMutationLogin