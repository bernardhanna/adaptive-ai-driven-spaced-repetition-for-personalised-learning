import React, { useEffect, useState } from 'react';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth }) {
    const [metrics, setMetrics] = useState({
        totalQuizzesTaken: 0,
        averageScore: 0,
        completionRate: 0,
        averageTimePerQuiz: 0,
    });

    useEffect(() => {
        axios.get('/api/user-metrics')
            .then(response => setMetrics(response.data))
            .catch(error => console.error('Error fetching user metrics:', error));
    }, []);

    return (
        <AuthenticatedLayout user={auth.user} header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}>
            <Head title="Dashboard" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3>Your Quiz Performance Metrics</h3>
                            <ul>
                                <li>Total Quizzes Taken: {metrics.totalQuizzesTaken}</li>
                                <li>Average Score: {metrics.averageScore}</li>
                                <li>Completion Rate: {metrics.completionRate}%</li>
                                <li>Average Time Per Quiz: {metrics.averageTimePerQuiz} minutes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
