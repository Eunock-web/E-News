import { apiFetch } from "../../Infrastructure/api"
import type { LoginInputs, RegisterInputs } from "../types"

export const FetchLogin = async (data: LoginInputs | RegisterInputs) => {
   return await apiFetch('auth/login', 'POST', data)
}