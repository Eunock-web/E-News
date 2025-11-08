
const BASEURL = 'http://localhost:8080/'; 

interface option  {
    'Content-Type': 'application/json',
    'Authorization': string,
    data?: object,
    method: string
};

export async function apiFetch(url:string, method = 'GET', data?:object ){
    const options: option = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer',
        data: {},
        method: method
    }
    if(data) {
        method = 'POST'
        options.data = data
    }else {
        delete options.data
    }
    
    try {
        await fetch(BASEURL + url, options).then(response =>{ 
            if(response.ok){
            return response.json()
            }
            throw new Error(`Status: ${response.status}, No response. Error raised`) 
        }
        ).catch(err => console.error(err))
    }catch(error){
        console.warn(error)
    }
}
    