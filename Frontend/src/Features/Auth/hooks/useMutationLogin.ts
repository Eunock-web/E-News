import { useMutation } from "@tanstack/react-query"
import { FetchLogin } from "../authApi"
import type { LoginInputs } from "../../types"

const useMutationLogin = () => {
    const mutation = useMutation({
        mutationKey: ['login'],
        mutationFn: (data: LoginInputs ) => FetchLogin(data)
    })
    if(mutation.isSuccess){

    }    
  return (
    ''
  )
}

export default useMutationLogin