import { apiFetch } from "../../Infrastructure/api"
import type { LoginInputs, RegisterInputs } from "../types"

export type LoginResponse = {
  message: string,
  success: boolean
}
export const FetchLogin = async (data: LoginInputs) => {
   const response: LoginResponse = await apiFetch('auth/login', 'POST', data);
   return response;
}

export const FetchRegister = async (data:RegisterInputs) =>{
   const response: LoginResponse = await apiFetch('email/verify', 'POST', data);
   return response;
}

