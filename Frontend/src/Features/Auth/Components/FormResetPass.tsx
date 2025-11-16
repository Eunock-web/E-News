import { Controller } from 'react-hook-form'
import useFormResetPass from '../hooks/Form/useFormResetPass'
import TextField from '../../../Utils/Components/TextField/TextField';
import { passwordProps } from '../../types';
import Button from '../../../Utils/Components/Button/Button';
import { Link } from 'react-router';

const FormResetPass = () => {
    const {control, onSubmit} = useFormResetPass();
  return (
    <form onSubmit={onSubmit} className='w-[30%] m-auto h-max'>
        <h1 className='text-3xl'>Reset Password</h1>
        <hr className='my-5 border-gray-400'/>
        <Controller 
            control={control}
            rules={{required:true}}
            name='newPassword'
            render={({field, fieldState: {error}})=> <TextField {...passwordProps} label='New Password' {...field} errorMessage={field.value ? error?.message : ''} />}
        />
        <Controller 
            control={control}
            rules={{required:true}}
            name='password_confirmation'
            render={({field, fieldState: {error}})=> <TextField {...passwordProps} {...field} label='Confirm Password' errorMessage={field.value ? error?.message : ''}/>}
        />
        <div className='flex justify-end gap-3 mt-5'>
            <Link className='w-[35%] bg-gray-400 rounded-md py-2 h-max text-white font-semibold text-center duration-200 hover:scale-95 hover:opacity-70' to={'/login'}>Cancel</Link>
            <Button textContent='Save password' type='submit' icon="" className='w-[40%] bg-(--bg-primary) py-2 text-white' />
        </div>
        
    </form>
  )
}

export default FormResetPass