import React, { Fragment, useEffect, useMemo, useState } from 'react'
import { useDropzone } from 'react-dropzone'
import { useTranslation } from 'react-i18next'

import axios from 'axios'

import {   dropzoneAcceptStyle,
    dropzoneActiveStyle,
    dropzoneBaseStyle,
    dropzoneRejectStyle } from './OldFileUploadWidget'
import { ContentWidgetProps } from '../utils'
import { WhiteCard } from '@newageerp/v3.widgets.white-card'
import OldLoaderLogo from './OldLoaderLogo';
import OldTooltip from './OldTooltip'
import { OldUiH5 } from './OldUiH5'
import OldFilesContentItem from './OldFilesContentItem';
import OldPopup from './OldPopup'
import OldEmailForm from './OldEmailForm'
import { getLinkForFile } from './OldFileFieldRo'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'

const defaultActions = ['download', 'preview', 'mail']

interface IFileFromServer {
  path: string
  filename: string
  extension: string
  deleted: boolean
  approved: boolean
  id: number
}

export default function OldFilesWidget(props: ContentWidgetProps) {
  const [emailPopup, setEmailPopup] = useState(false)
  const toggleEmailPopup = () => setEmailPopup(!emailPopup)

  const { t } = useTranslation()
  const [showDeleted, setShowDeleted] = useState(false)
  const toggleShowDeleted = () => setShowDeleted(!showDeleted)
  const {
    acceptedFiles,
    getRootProps,
    getInputProps,
    isDragActive,
    isDragAccept,
    isDragReject
  } = useDropzone()

  const isReadOnly = !!props.options && props.options.readOnly

  const fileActions = props.options.actions
    ? props.options.actions
    : defaultActions

  const [isUploading, setIsUploading] = useState(false)
  const [getData, getDataParams] = OpenApi.useURequest<IFileFromServer>('NAEfilesList')

  const type = props.options.type ? props.options.type : 10
  const folder = props.schema + '/' + props.element.id + '/' + type

  const loadData = () => {
    getData({ folder }).then((res: any) => {
      const eventFilesWidgetLoaded = new CustomEvent('FilesWidgetLoaded', {
        detail: {
          key: type,
          data: res.data.data.filter((f: any) => !f.deleted),
          title: options.title ? options.title : 'Failai',
          id: props.element.id,
          schema: props.schema,
        }
      })
      window.dispatchEvent(eventFilesWidgetLoaded)
    })
  }
  useEffect(loadData, [props.schema, props.element.id])

  const downloadZip = () => {
    const link = getLinkForFolder(folder)
    window.open(link)
  }

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
        .then(() => {
          setIsUploading(false)
          loadData()
          if (props.options.onReload) {
            props.options.onReload()
          }
        })
    }
  }, [acceptedFiles])

  const style: any = useMemo(
    () => ({
      ...dropzoneBaseStyle,
      ...(isDragActive ? dropzoneActiveStyle : {}),
      ...(isDragAccept ? dropzoneAcceptStyle : {}),
      ...(isDragReject ? dropzoneRejectStyle : {})
    }),
    [isDragActive, isDragReject, isDragAccept]
  )

  const { options } = props

  const isData =
    !!getDataParams.data &&
    !!getDataParams.data.data &&
    getDataParams.data.data.length > 0

  const hasDeleted =
    isData && getDataParams.data.data.filter((f: any) => f.deleted).length > 0

  const filesToParse: IFileFromServer[] = isData ? getDataParams.data.data : []

  const className = []
  if (props.options.className) {
    className.push(props.options.className)
  }
  if (showDeleted) {
    className.push('border-2-yellow-500')
  }

  const filesToRender = filesToParse.filter((f: any) =>
    showDeleted ? f.deleted : !f.deleted
  )

  return (
    <WhiteCard isCompact={true} className={className.join(' ')}>
      {props.options.skipHeader && isUploading && (
        <div className={'text-center'}>
          <OldLoaderLogo size={20} />
        </div>
      )}

      {!props.options.skipHeader && (
        <div className={'flex gap-2 items-center'}>
          {!!props.options.hint && <OldTooltip text={props.options.hint} />}
          <div className={'flex-grow'}>
            <OldUiH5 icon={'fad fa-folders fa-fw'}>
              {t(options.title ? options.title : 'Failai')}
            </OldUiH5>
          </div>
          {hasDeleted && !props.options.hideDeletedFiles && (
            <button onClick={() => toggleShowDeleted()}>
              <i
                className={
                  'fad fa-archive ' + (showDeleted ? 'text-yellow-500' : '')
                }
              />
            </button>
          )}

          {filesToRender.length > 1 && !showDeleted && (
            <button onClick={downloadZip}>
              <i className={'fad fa-file-archive text-nsecondary-600'} />
            </button>
          )}

          {filesToRender.length > 1 && !showDeleted && (
            <button onClick={toggleEmailPopup}>
              <i className={'fad fa-paper-plane text-nsecondary-600'} />
            </button>
          )}

          {isUploading && <OldLoaderLogo size={20} />}
        </div>
      )}

      {!isReadOnly && (
        <div className={'grid gap-1'}>
          <div {...getRootProps({ style })}>
            <Fragment>
              <input {...getInputProps()} />
              <p>{t('Tempkite failus arba paspauskite')}</p>
            </Fragment>
          </div>
        </div>
      )}

      {isData && (
        <div className={'grid gap-1'}>
          {filesToRender.map((f: any, index: number) => {
            return (
              <OldFilesContentItem
                key={'file-' + props.element.id + '-' + f.id + '-' + index}
                f={f}
                reloadData={loadData}
                otherFiles={filesToParse}
                readOnly={isReadOnly}
                type={type}
                parentSchema={props.schema}
                parentElementId={props.element.id}
                fileActions={fileActions}
                mailExtraData={props.options.mailExtraData}
              />
            )
          })}
        </div>
      )}

      <OldPopup visible={emailPopup} toggleVisible={toggleEmailPopup}>
        <OldEmailForm
          extraData={{
            id: -1,
            schema: props.schema,
            template: 'folder-' + type,
            ...props.options.mailExtraData
          }}
          files={filesToRender.map((f: IFileFromServer) => {
            return {
              name: f.filename,
              link: getLinkForFile(f)
            }
          })}
          onSend={toggleEmailPopup}
        />
      </OldPopup>
    </WhiteCard>
  )
}

export const getLinkForFolder = (folder: string): string => {
  return (
    window.location.origin +
    '/app/nae-core/files/download-zip?f=' +
    encodeURIComponent(
      JSON.stringify({
        folder: folder
      })
    ) +
    '&token=' +
    window.localStorage.getItem('token')
  )
}