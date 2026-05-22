import { useEffect, useState } from 'react'
import api from '../services/api'
import '../styles/Home.css'

function Home() {
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true)
        // Replace with your actual API endpoint
        const response = await api.get('/products')
        setData(response.data)
      } catch (err) {
        setError(err.message)
      } finally {
        setLoading(false)
      }
    }

    fetchData()
  }, [])

  if (loading) return <div className="home"><p>Loading...</p></div>
  if (error) return <div className="home"><p>Error: {error}</p></div>

  return (
    <div className="home">
      <h1>Welcome to KP PlanShop React Frontend</h1>
      <p>Your React frontend is ready!</p>
    </div>
  )
}

export default Home
