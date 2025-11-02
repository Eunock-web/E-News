import { Controller } from 'react-hook-form'
import useLoginForm from '../hooks/useLoginForm'
import TextField from '../../../Utils/Components/TextField/TextField';
import type { IButtonProps, ITextFieldProps } from '../../../Utils/Components/types';
import { Info } from 'lucide-react';
import Button from '../../../Utils/Components/Button/Button';

const FormLogin = () => {
  const {control, states, onSubmit} = useLoginForm();
  const emailProps: ITextFieldProps = {
    type: 'email',
    label: 'Email',
    placeholder: 'name@example.com',
    onChange: () => {}
  }
  
  const passwordProps: ITextFieldProps = {
    type: 'password',
    label: 'Password',
    placeholder:'••••••••••',
    onChange: () => {}
  }
  
  const buttonProps: IButtonProps = {
    type:'submit',
    textContent: 'Login',
    icon:'',
    className: 'bg-(--bg-primary) w-1/2 py-2 m-auto mt-5 text-white font-semibold',
  }

  return (
    <form className='w-[35%] rounded-xl p-4 m-auto h-max' onSubmit={onSubmit}>
        <h1 className='text-2xl '>Welcome back!</h1>

        <Controller 
          control={control}
          name='email'
          rules={{required:true}}
          render={({field}) => <TextField {...emailProps} onChange={field.onChange} />}
        />

        <Controller 
          control={control}
          name='password'
          rules={{required:true}}
          render={({field}) => <TextField {...passwordProps} onChange={field.onChange}/>}
        />
        <p className='w-max flex justify-self-end mt-5 hover:text-(--bg-primary) cursor-pointer'>Forgot password ?</p>

        {states.errors.root && <p><i data-lucide={Info}></i> {states.errors.root.message}</p>}  

        <Button {...buttonProps}/>      
    </form>
  )
}

export default FormLogin