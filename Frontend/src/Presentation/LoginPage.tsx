import FormLogin from '../Features/Auth/Login/FormLogin'

const LoginPresentation = () => {
  return (
    <div className='w-full items-baseline h-full'>
        <div className='absolute left-10 top-10'>
            <h1 className='text-5xl font-bold'>E-News</h1>
            <p className='text-sm font-medium'>La minute info digitale</p>
        </div>
        <div className='flex m-auto h-full w-full'>
            <FormLogin/>
        </div>
        
    </div>
  )
}

export default LoginPresentation