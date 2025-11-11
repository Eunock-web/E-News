import { useForm } from 'react-hook-form'
import { LoginSchema, type LoginInputs } from '../../../types'
import { zodResolver } from '@hookform/resolvers/zod';
import useMutationLogin from '../Mutation/useMutationLogin';
import { useNavigate } from 'react-router';
/**
 * React Hook Form for login process handling.
 * @returns 
 */
const useFormLogin = () => {
  const form = useForm<LoginInputs>({
    resolver: zodResolver(LoginSchema)
  });

  const mutation = useMutationLogin();
  const navigate = useNavigate();

  const onSubmit = (data: LoginInputs) => {
    mutation.mutate(data);
    if(mutation.isSuccess)
      navigate('/home')
  }

  return {
      control: form.control,
      states: form.formState,
      onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
  }
}

export default useFormLogin