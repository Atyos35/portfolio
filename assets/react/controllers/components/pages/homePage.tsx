import React, { useEffect, useState } from 'react';
import UserPage from './userPage';
import ExperiencePage from './experiencePage';
import TrainingPage from './trainingPage';
import BlockPage from './blockPage';
import { motion, AnimatePresence } from 'framer-motion';

const Loader = require('../../../../images/waiter.gif');

type HomePageProps = {
  user: any;
  experiences: any;
  trainings: any;
  blocks: any;
  editUserAction: string;
  csrfTokens: {
    user: string;
    experience: string;
    training: string;
    block: string;
  };
};

export default function HomePage({
  user,
  experiences,
  trainings,
  blocks,
  csrfTokens,
  editUserAction
}: HomePageProps) {
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const delay = setTimeout(() => {
      setIsLoading(false);
    }, 2000);
    return () => clearTimeout(delay);
  }, []);

  return (
    <AnimatePresence mode="wait">
      {isLoading ? (
        <motion.div
          key="loader"
          className="flex-1 w-full py-8 px-2 sm:px-4 lg:px-0 flex items-center justify-center"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.6 }}
        >
          <img src={Loader} alt="Chargement..." className="w-30 h-30" />
        </motion.div>
      ) : (
        <motion.div
          key="content"
          className="flex-1 w-full py-8 px-2 sm:px-4 lg:px-0"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -20 }}
          transition={{ duration: 0.6, ease: 'easeOut' }}
        >
          <div className="max-w-6xl mx-auto border border-gray-300 bg-white rounded-2xl p-6 lg:p-8 section-background">
            <div className="flex flex-col gap-8">
              <UserPage editUserAction={editUserAction} user={user} csrfToken={csrfTokens.user} />
              <div className="flex flex-col lg:flex-row gap-8">
                <div className="flex flex-col gap-8 w-full lg:w-2/3">
                  <ExperiencePage experiences={experiences} csrfToken={csrfTokens.experience} />
                  <TrainingPage trainings={trainings} csrfToken={csrfTokens.training} />
                </div>
                <aside className="w-full lg:w-1/3 max-w-md">
                  <BlockPage blocks={blocks} csrfToken={csrfTokens.block} />
                </aside>
              </div>
            </div>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}