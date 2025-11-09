import { apiFetch } from "../../Infrastructure/api"
import type { LoginInputs, RegisterInputs } from "../types"

export type LoginResponse = {
  message: string
}
export const FetchLogin = async (data: LoginInputs | RegisterInputs) => {
   const response: LoginResponse = await apiFetch('auth/login', 'POST', data);
   return response;
}