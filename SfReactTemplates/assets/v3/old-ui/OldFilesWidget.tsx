import React, { Fragment, useEffect, useMemo, useState } from 'react'
import { useDropzone } from 'react-dropzone'
import { useTranslation } from 'react-i18next'

import axios from 'axios'

import {
  dropzoneAcceptStyle,
  dropzoneActiveStyle,
  dropzoneBaseStyle,
  dropzoneRejectStyle
} from './OldFileUploadWidget'
import { ContentWidgetProps } from '../utils'
import { WhiteCard } from '@newageerp/v3.bundles.widgets-bundle'
import OldLoaderLogo from './OldLoaderLogo';
import { OldUiH5 } from './OldUiH5'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import { Tooltip } from "@newageerp/v3.bundles.badges-bundle";

import { FileLine } from '@newageerp/ui.components.element.file-line'

const defaultActions = ['download', 'preview', 'mail', 'remove']

interface IFileFromServer {
  path: string
  filename: string
  extension: string
  deleted: boolean
  approved: boolean
  id: number
}

export default function OldFilesWidget(props: ContentWidgetProps) {
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

  const onDownload = (id: number) => {
    const url = `/app/nae-core/files/viewById?id=${id}&download=true`;
    window.open(url);
  };
  const onRemove = (id: number) => {
    const url = `/app/nae-core/files/removeById`;
    axios
      .post(
        url,
        { id },
        {
          headers: {
            // @ts-ignore
            Authorization: localStorage.getItem('token'),
          },
        }
      )
      .then((response) => {
        if (response.status === 200) {
          loadData();
        }
      });
  };

  const onApprove = (id: number) => {
    const url = `/app/nae-core/files/toggleApproved`;
    axios
      .post(
        url,
        { id },
        {
          headers: {
            // @ts-ignore
            Authorization: localStorage.getItem('token'),
          },
        }
      )
      .then((response) => {
        if (response.status === 200) {
          loadData();
        }
      });
  };

  const filesToRenderNew: any = filesToRender.map((file: IFileFromServer) => {
    const viewUrl = `/app/nae-core/files/viewById?id=${file.id}&token=${window.localStorage.getItem('token')}`;

    const canView = fileActions.indexOf('preview') >= 0;
    const canDownload = fileActions.indexOf('download') >= 0;
    const canRemove = fileActions.indexOf('remove') >= 0;
    const canApprove = fileActions.indexOf('check') >= 0;
    const canSend = fileActions.indexOf('send') >= 0;

    const fileProps: any = {
      folderInfo: {
        parentEntity: folder,
        parentElementId: props.element.id,
        parentType: type,
      },
      props: {
        title: file.filename,
        isApproved: file.approved,
        onDownload: canDownload
          ? () => onDownload(file.id)
          : undefined,
        onRemove: canRemove ? () => onRemove(file.id) : undefined,
        onApprove: canApprove ? () => onApprove(file.id) : undefined,
        onView: canView
          ? {
            link: viewUrl,
            ext: file.filename.split('.').pop(),
            id: file.id,
          }
          : undefined,
      },
      file: file,
    };
    if (canSend) {
      fileProps.props.onSend = () => {
        const options = {
          extraData: {
            id: fileProps.props.onView.id,
            schema: 'file',
            parentSchema: fileProps.folderInfo.parentEntity,
            parentElementId: fileProps.folderInfo.parentElementId,
            template: fileProps.folderInfo.parentType,
          },
          files: [
            {
              name: fileProps.props.title,
              link: fileProps.props.onView.link,
            },
          ]
        }
        const event = new CustomEvent(
          'SFSOpenEmailForm',
          {
            detail: options
          }
        );
        window.dispatchEvent(event);
      }
    }
    return fileProps;
  });

  return (
    <WhiteCard isCompact={true} className={className.join(' ')}>
      {props.options.skipHeader && isUploading && (
        <div className={'text-center'}>
          <OldLoaderLogo size={20} />
        </div>
      )}

      {!props.options.skipHeader && (
        <div className={'flex gap-2 items-center'}>
          {!!props.options.hint && <Tooltip text={props.options.hint} />}
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
            <button onClick={() => {
              const options = {
                extraData: {
                  id: -1,
                  schema: 'file',
                  parentSchema: props.schema,
                  parentElementId: -1,
                  template: 'folder-' + type,
                },
                files: filesToRenderNew.map((f: any) => {
                  return ({
                    name: f.props.title,
                    link: f.props.onView.link,
                  })
                })
              }
              const event = new CustomEvent(
                'SFSOpenEmailForm',
                {
                  detail: options
                }
              );
              window.dispatchEvent(event);
            }}>
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
            const fileLineProps = filesToRenderNew[index].props;
            return (
              <Fragment key={'file-' + props.element.id + '-' + f.id + '-' + index}>
                <FileLine
                  {...fileLineProps}
                  onClick={() => {

                    const options = {
                      file: fileLineProps,
                      otherFiles: filesToRenderNew.map((f: any) => f.props)
                    }
                    const event = new CustomEvent(
                      'SFSOpenFilePreview',
                      {
                        detail: options
                      }
                    );
                    window.dispatchEvent(event);
                  }
                  }
                />
              </Fragment>
            )
          })}
        </div>
      )}

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