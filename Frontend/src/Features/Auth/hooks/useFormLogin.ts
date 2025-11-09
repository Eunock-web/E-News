import { useForm } from 'react-hook-form'
import { LoginSchema, type LoginInputs } from '../../types'
import { zodResolver } from '@hookform/resolvers/zod';
import useMutationLogin from './useMutationLogin';
/**
 * React Hook Form for login process handling.
 * @returns 
 */
const useFormLogin = () => {
  const form = useForm<LoginInputs>({
    resolver: zodResolver(LoginSchema)
  });
  const mutation = useMutationLogin();

  const onSubmit = (data: LoginInputs) => {
    mutation.mutate(data);
  }

  return {
    control: form.control,
    states: form.formState,
    onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
  }
}

export default useFormLogin