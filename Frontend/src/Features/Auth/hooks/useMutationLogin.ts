import { useMutation } from "@tanstack/react-query"
import { FetchLogin, type LoginResponse } from "../authApi"
import type { LoginInputs } from "../../types"
import useNotificationManager from "../../../Utils/Components/Notification/hooks/useNotificationManager"



const useMutationLogin = () => {
  const {notify} = useNotificationManager();
  const mutation =  useMutation<LoginResponse, void, LoginInputs>({
        mutationKey: ['login'],
        mutationFn: (data: LoginInputs) => FetchLogin(data),
        onSuccess: () => {
            if(mutation.isSuccess)
              if(mutation.data)
                notify("Login Succesfully!", 'success');
        }, 
        onError: (error)=> {
          notify("An error occured!", "error");
          console.log(error);
          
        }
    })
    return mutation
}

export default useMutationLogin