import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';
import React, { useEffect, useState } from 'react'
import { axiosInstance } from '../api/config';

type Props = {
  sourceSchema: string,
  sourceId: number,
  user: number,
}

export default function ElementBookmarkButton(props: Props) {
  const [exists, setExists] = useState(false);

  const toggleBookmark = () => {
    const url = '/app/proxy/bookmarks/toggle';
    axiosInstance
      .post(
        url,
        { data: props }
      )
      .then((response) => {
        loadExistingBookmark();
      });
  };
  const loadExistingBookmark = () => {
    const url = '/app/proxy/bookmarks/get';
    axiosInstance
      .post(
        url,
        { data: props }
      )
      .then((response) => {
        if (response.status === 200) {
          console.log('response.data', response.data);
          setExists(response.data.success === 1);
        } else {
          // UIConfig.toast.error(t('Klaida'))
        }
      });
  };
  useEffect(loadExistingBookmark, []);

  return (
    <ToolbarButton iconName='bookmark' bgColor={exists ? 'tw3-bg-teal-50' : undefined} textColor={exists ? 'tw3-text-teal-700' : undefined} onClick={toggleBookmark} />
  )
}
