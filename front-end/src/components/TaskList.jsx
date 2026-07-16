import { useState, useEffect } from 'react';
import api from '../api';

function TaskList() {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    api.get('/tasks')
      .then((response) => {
        if (response.data.status) {
          setTasks(response.data.data);
        } else {
          setError('Failed to load tasks');
        }
      })
      .catch((err) => {
        console.error(err);
        setError('Could not connect to the server');
      })
      .finally(() => setLoading(false)); 
  }, []); // empty dependency array = run once when component mounts

  if (loading) return <p>Loading tasks...</p>;
  if (error) return <p style={{ color: 'red' }}>{error}</p>;

  return (
    <div>
      <h2>Tasks</h2>
      <ul>
        {tasks.map((task) => (
          <li key={task.task_id}>
            <strong>{task.title}</strong> — {task.status} (due {task.due_date})
          </li>
        ))}
      </ul>
    </div>
  );
}

export default TaskList;