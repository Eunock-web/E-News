import { Outlet } from "react-router"

const AuthLayout = () => {
  return (
    <div className='w-full items-baseline h-full'>
        <div className='w-1/2 h-1/10 scale-60 -translate-[22%] relative left-10 top-10'>
            <h1 className='text-5xl font-bold'>E-News</h1>
            <p className='text-sm font-medium'>La minute info digitale</p>
        </div>

        <div className="flex m-auto h-9/10 w-full">
            <Outlet/>
        </div>
    </div>
  )
}

export default AuthLayout