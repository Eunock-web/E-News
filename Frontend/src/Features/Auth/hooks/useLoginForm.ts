import { useForm } from 'react-hook-form'
import { LoginSchema, type LoginInputs } from '../../types'
import { zodResolver } from '@hookform/resolvers/zod';
/**
 * React Hook Form for login process handling.
 * @returns 
 */
const useLoginForm = () => {
  const form = useForm<LoginInputs>({
    resolver: zodResolver(LoginSchema)
  });

  const onSubmit = (data: LoginInputs) => {
    console.log(data)
  }

  return {
    control: form.control,
    states: form.formState,
    onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
  }
}

export default useLoginForm