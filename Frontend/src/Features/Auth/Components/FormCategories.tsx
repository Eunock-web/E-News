import { useEffect } from "react"
import useStoreDefault, { useFetchCategories } from "../../store"
import useNotificationManager from "../../../Utils/Components/Notification/hooks/useNotificationManager";

const FormCategories = () => {
    const categories = useStoreDefault((state) => state.categories)
    const { data, isSuccess } = useFetchCategories( Array.isArray(categories) );
    const { notify } = useNotificationManager();

    useEffect(() => {
        if (isSuccess && data && Array.isArray(data)) {
            useStoreDefault.setState({ categories: data });
        }

        if(data && !Array.isArray(data))
          notify(data.message, 'error');

    }, [data, isSuccess]);
    
  return (
    <form action="" className="w-[80%] m-auto text-center">
        <h1>Choose some categories of news you want your feed to be full of.</h1>
        <ul className="flex items-center gap-3 w-[80%]">
          {Array.isArray(categories) && categories.map((cat, index) => <li key={index} className="px-3 py-2 rounded-2xl border border-gray-600">{cat}</li>)}
        </ul>
    </form>
  )
}

export default FormCategories