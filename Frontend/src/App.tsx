import { Outlet } from 'react-router'
import './App.css'
import NotificationProvider from './Utils/Components/Notification/NotificationProvider'
import NotificationContainer from './Utils/Components/Notification/NotificationContainer'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'

const App = () => {
  const client = new QueryClient;

  return (
      <QueryClientProvider client={client}>
          <NotificationProvider>
            <Outlet/>
            <NotificationContainer/>
          </NotificationProvider>
      </QueryClientProvider>
        
  )
}

export default App