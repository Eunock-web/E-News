import { Controller } from 'react-hook-form'
import useFormLogin from '../hooks/useFormLogin'
import TextField from '../../../Utils/Components/TextField/TextField';
import type { IButtonProps, ITextFieldProps } from '../../../Utils/Components/types';
import { Info } from 'lucide-react';
import Button from '../../../Utils/Components/Button/Button';
import { Link } from 'react-router';

const FormLogin = () => {
  const {control, states, onSubmit} = useFormLogin();
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
        <hr className='my-3 border-gray-300' />
      
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
        <p className='text-center mt-5'>Just discovered E-News? <Link to='/register' className='text-(--bg-primary) duration-300 border-b-2 border-transparent hover:border-b-(--bg-primary)'>Register here.</Link></p>
    </form>
  )
}

export default FormLogin