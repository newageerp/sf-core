import React, { Fragment, useMemo, useEffect, useState } from 'react'
import { useDropzone } from 'react-dropzone'
import { useTranslation } from 'react-i18next'
import moment from 'moment'

import axios from 'axios'
import { WhiteCard } from '@newageerp/v3.bundles.widgets-bundle'
import OldLoaderLogo from './OldLoaderLogo'
import { TextCardTitle } from '@newageerp/v3.bundles.typography-bundle'

interface Props {
  type: string
  onUpload: (f: any) => void
  folderPrefix?: string,

  hideCard?: boolean,
  hideTitle?: boolean,
}

export default function FileUploadWidget(props: Props) {
  const { t } = useTranslation()

  const folderPrefix = props.folderPrefix ? props.folderPrefix : 'tmp';

  const [isUploading, setIsUploading] = useState(false)

  const {
    acceptedFiles,
    getRootProps,
    getInputProps,
    isDragActive,
    isDragAccept,
    isDragReject
  } = useDropzone()

  const style: any = useMemo(
    () => ({
      ...dropzoneBaseStyle,
      ...(isDragActive ? dropzoneActiveStyle : {}),
      ...(isDragAccept ? dropzoneAcceptStyle : {}),
      ...(isDragReject ? dropzoneRejectStyle : {})
    }),
    [isDragActive, isDragReject, isDragAccept]
  )

  const folder = folderPrefix + '/' + props.type + '/' + moment().format('YYYY-MM-DD')

  // @ts-ignore
  const token: string = window.localStorage.getItem('token')

  useEffect(() => {
    if (acceptedFiles.length > 0) {
      setIsUploading(true)
      const fData = new FormData()
      fData.append('folder', folder)
      acceptedFiles.forEach((file: File, index: number) => {
        // @ts-ignore
        fData.append('f-' + index, file)
      })

      axios
        .post('/app/nae-core/files/upload', fData, {
          headers: {
            Authorization: token,
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((res) => {
          setIsUploading(false);
          if (props.onUpload) {
            props.onUpload(res.data.data);
          }
        })
    }
  }, [acceptedFiles])

  const titleComp = props.hideTitle ? <Fragment /> : <TextCardTitle className={'flex-grow'}>
    {t('Failų įkėlimas')}
  </TextCardTitle>;

  const WrapComp = props.hideCard ? Fragment : WhiteCard;

  return (
    <WrapComp>
      {titleComp}
      {isUploading && (<OldLoaderLogo size={20} />)}
      <div className={'grid gap-1'}>
        <div {...getRootProps({ style })}>
          <Fragment>
            <input {...getInputProps()} />
            <p>{t('Tempkite failus arba paspauskite')}</p>
          </Fragment>
        </div>
      </div>
    </WrapComp>
  )
}

const dropzoneBaseStyle = {
    flex: 1,
    display: "flex",
    flexDirection: "column",
    alignItems: "center",
    padding: "7px",
    borderWidth: 2,
    borderRadius: 2,
    borderColor: "#eeeeee",
    borderStyle: "dashed",
    backgroundColor: "#fafafa",
    color: "#bdbdbd",
    outline: "none",
    transition: "border .24s ease-in-out",
  };
  
  const dropzoneActiveStyle = {
    borderColor: "#2196f3",
  };
  
  const dropzoneAcceptStyle = {
    borderColor: "#00e676",
  };
  
  const dropzoneRejectStyle = {
    borderColor: "#ff1744",
  };
  
  export {
    dropzoneBaseStyle,
    dropzoneActiveStyle,
    dropzoneAcceptStyle,
    dropzoneRejectStyle,
  };
  