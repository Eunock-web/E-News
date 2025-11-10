import { useMutation } from "@tanstack/react-query"
import { FetchRegister } from "../authApi"
import type { RegisterInputs } from "../../types"

export type RegisterResponse = {

}
export const useMutationRegister = () => {
    const mutation = useMutation({
        mutationKey:['register'],
        mutationFn: (data:RegisterInputs)=>FetchRegister(data)
    })
  return (
    ''
  )
}
