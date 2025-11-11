import { apiFetch } from "../../Infrastructure/api"

export type LoginResponse = {
  message: string,
  success: boolean
}
export const FetchAuth = async (url:string, data:object) =>{
   const response: LoginResponse = await apiFetch('auth/' + url, 'POST', data);
   return response;
}