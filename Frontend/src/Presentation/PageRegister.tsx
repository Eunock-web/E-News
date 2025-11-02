import FormRegister from '../Features/Auth/Register/FormRegister'

const PageRegister = () => {
  return (
    <div className='w-full h-full flex'>
        <div className='absolute left-10 top-10'>
            <h1 className='text-5xl font-bold'>E-News</h1>
            <p className='text-sm font-medium'>La minute info digitale</p>
        </div>
        <FormRegister/>
    </div>
  )
}

export default PageRegister